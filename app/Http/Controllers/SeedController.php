<?php

namespace App\Http\Controllers;

use App\Models\Seed;
use App\Models\SeedTraceability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SeedController extends Controller
{
    /**
     * ១. មុខងារសម្រាប់បើកបង្ហាញ Form បន្ថែមគ្រាប់ពូជថ្មី
     */
    public function create()
    {
        // ទាញយកប្រភេទបន្លែទាំងអស់ពី Database តាមរយៈ Eloquent Model
        $categories = \App\Models\Category::all();
        return view('admin.create_seed', compact('categories'));
    }

    /**
     * ២. មុខងារសម្រាប់ចាប់ទិន្នន័យពី Form ទៅរក្សាទុកក្នុង Database (Store)
     */
    public function store(Request $request)
    {
        // ផ្ទៀងផ្ទាត់ទិន្នន័យពី Form បង្កើតថ្មី (Validation) ឱ្យវាស្គាល់ Fields ទាំងអស់រួមទាំងព័ត៌មានកសិកម្មលម្អិត
        $request->validate([
            'name_kh'           => 'required|string|max:255',
            'name_en'           => 'required|string|max:255',
            'price'             => 'required|numeric',
            'stock'             => 'required|integer',
            'category_id'       => 'required|integer',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // ផ្ទៀងផ្ទាត់ដុំកូដវគ្គផលិតកម្ម (Traceability)
            'batch_number'      => 'required|string|unique:seed_traceabilities,batch_number',
            'origin_kh'         => 'required|string|max:255',
            'germination_rate'  => 'required|numeric|min:0|max:100',
            'harvest_date'      => 'required|string|max:255',
            'expiry_date'       => 'required|string|max:255',
            'farm_name'         => 'nullable|string|max:255',
            'soil_ph'           => 'nullable|string|max:50',
            'watering_schedule' => 'nullable|string|max:255',
            'cultivation_guide' => 'nullable|string',
        ]);

        // កូដសម្រាប់ Upload រូបភាព
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/seeds'), $imageName);
        }

        // បង្កើតគ្រាប់ពូជចូលទៅក្នុងតារាង `seeds` មុនគេបង្អស់
        $seed = Seed::create([
            'category_id'  => $request->category_id,
            'name_kh'      => $request->name_kh,
            'name_en'      => $request->name_en,
            'price'        => $request->price,
            'stock'        => $request->stock,
            'image'        => $imageName,
            'batch_number' => $request->batch_number, // ភ្ជាប់លេខបាច់ទៅតារាងគ្រាប់ពូជផ្ទាល់
        ]);

        // បង្កើតទិន្នន័យវគ្គផលិតចូលតារាង seed_traceabilities ព្រមគ្នា ដោយចាក់សោរការពារ 'origin_en'
        SeedTraceability::create([
            'seed_id'           => $seed->id, // 🔗 ភ្ជាប់ជាមួយ ID គ្រាប់ពូជដែលទើបតែបង្កើតខាងលើ
            'batch_number'      => $request->batch_number,
            'origin_kh'         => $request->origin_kh,

            // 🔒 ចាក់សោរសុវត្ថិភាពខ្ពស់បំផុត៖ ការពារកំហុស "origin_en doesn't have a default value"
            'origin_en'         => $request->origin_en ?? ($request->origin_kh ?? 'Takream Village, Banan District, Battambang'),

            'germination_rate'  => $request->germination_rate,
            'harvest_date'      => $request->harvest_date,
            'expiry_date'       => $request->expiry_date,
            'farm_name'         => $request->farm_name,
            'soil_ph'           => $request->soil_ph,
            'watering_schedule' => $request->watering_schedule,
            'cultivation_guide' => $request->cultivation_guide,
        ]);

        return redirect('/admin/seeds')->with('success', 'បានបន្ថែមគ្រាប់ពូជថ្មី និងបង្កើតខ្សែសង្វាក់ផលិតកម្មដោយជោគជ័យ! 🌾');
    }

    /**
     * ៣. មុខងារសម្រាប់ទាញទិន្នន័យគ្រាប់ពូជទាំងអស់មកបង្ហាញក្នុងតារាង Admin
     */
    public function index()
    {
        // $seeds = Seed::with('category')->get();
        // return view('admin.seeds', compact('seeds'));

        $seeds = Seed::with('category')->orderBy('created_at', 'desc')->get();
        return view('admin.seeds', compact('seeds'));
    }

    /**
     * ៤. មុខងារសម្រាប់បង្ហាញទំព័រលម្អិតគ្រាប់ពូជសម្រាប់អ្នកប្រើប្រាស់ទូទៅ (Farmer Detail)
     */
    public function show(int $id)
    {
        $seed = Seed::with(['category', 'traceabilities'])->findOrFail($id);
        return view('detail', compact('seed'));
    }

    /**
     * ៥. មុខងារសម្រាប់បើកទំព័រ Form កែប្រែទិន្នន័យ (Edit Form)
     */
    public function edit(int $id)
    {
        $seed = Seed::with('traceability')->findOrFail($id);
        $categories = \App\Models\Category::all(); //  ថែមទាញ Categories ចូលទៅក្នុង Form Edit ការពារកំហុសទទេ
        return view('admin.edit_seed', compact('seed', 'categories'));
    }

    /**
     * ៦. មុខងារសម្រាប់ទទួលទិន្នន័យថ្មីទៅកែប្រែក្នុង Database (Update Logic)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name_kh'           => 'required|string|max:255',
            'name_en'           => 'required|string|max:255',
            'price'             => 'required|numeric',
            'stock'             => 'required|integer',
            'category_id'       => 'required|integer',
            'batch_number'      => 'required|string',
            'origin_kh'         => 'required|string|max:255',
            'germination_rate'  => 'required|numeric|min:0|max:100',
            'harvest_date'      => 'required|string|max:255',
            'expiry_date'       => 'required|string|max:255',
            'farm_name'         => 'nullable|string|max:255',
            'soil_ph'           => 'nullable|string|max:50',
            'watering_schedule' => 'nullable|string|max:255',
            'cultivation_guide' => 'nullable|string',
        ]);

        $seed = Seed::findOrFail($id);

        // កូដគ្រប់គ្រងរូបភាព
        $imageName = $seed->image;
        if ($request->hasFile('image')) {
            // លុបរូបភាពចាស់ចេញ (បើមាន)
            if ($seed->image && file_exists(public_path('uploads/seeds/' . $seed->image))) {
                unlink(public_path('uploads/seeds/' . $seed->image));
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/seeds'), $imageName);
        }

        // កែប្រែទិន្នន័យក្នុងតារាង seeds
        $seed->update([
            'category_id'  => $request->category_id,
            'name_kh'      => $request->name_kh,
            'name_en'      => $request->name_en,
            'price'        => $request->price,
            'stock'        => $request->stock,
            'image'        => $imageName,
            'batch_number' => $request->batch_number,
        ]);

        // 🔒 ចាក់សោរសុវត្ថិភាពខ្ពស់បំផុត៖ កែប្រែ ឬបង្កើតព័ត៌មានកសិកម្មដោយគ្មានថ្ងៃគាំង Error 'origin_en' ទៀតឡើយ
        SeedTraceability::updateOrCreate(
            ['seed_id' => $seed->id],
            [
                'batch_number'      => $request->batch_number,
                'origin_kh'         => $request->origin_kh,

                // ប្រព័ន្ធ Fallback ការពារតម្លៃទទេដាច់ខាត ១០០%
                'origin_en'         => $seed->traceability?->origin_en ?? ($request->origin_en ?? 'Takream Village, Banan District, Battambang'),

                'germination_rate'  => $request->germination_rate,
                'harvest_date'      => $request->harvest_date,
                'expiry_date'       => $request->expiry_date,
                'farm_name'         => $request->farm_name,
                'soil_ph'           => $request->soil_ph,
                'watering_schedule' => $request->watering_schedule,
                'cultivation_guide' => $request->cultivation_guide,
            ]
        );

        return redirect('/admin/seeds')->with('success', 'កែប្រែព័ត៌មានគ្រាប់ពូជ និងវគ្គផលិតកម្មបានជោគជ័យ!🌾 ');
    }

    /**
     * ៧. មុខងារសម្រាប់លុបគ្រាប់ពូជចេញពីប្រព័ន្ធ (Delete)
     */
    public function destroy($id)
    {
        $seed = Seed::findOrFail($id);

        // ឆែកមើលលក្ខខណ្ឌការពារសុវត្ថិភាពទិន្នន័យ (Data Integrity Check)
        $hasOrders = \App\Models\OrderItem::where('seed_id', $id)->exists();
        if ($hasOrders) {
            return redirect()->back()->with('error', 'មិនអាចលុបគ្រាប់ពូជនេះបានទេ! ព្រោះវាមានជាប់ជំពាក់នៅក្នុងប្រវត្តិកម្ម៉ង់ទិញរបស់អតិថិជន។');
        }

        // លុបព័ត៌មានតាមដានប្រភពដែលជាកូនចោលមុន (បើមាន) ដើម្បីកុំឱ្យទាស់ Foreign Key
        SeedTraceability::where('seed_id', $id)->delete();

        // លុបរូបភាពចេញពីម៉ាស៊ីន
        if ($seed->image && file_exists(public_path('uploads/seeds/' . $seed->image))) {
            unlink(public_path('uploads/seeds/' . $seed->image));
        }

        $seed->delete();
        return redirect('/admin/seeds')->with('success', 'គ្រាប់ពូជត្រូវបានលុបចេញពីប្រព័ន្ធដោយជោគជ័យ!');
    }

    /**
     * ៨. មុខងារសម្រាប់បើក Form បញ្ចូលព័ត៌មានតាមដានប្រភពដើមដាច់ដោយឡែក (Add Batch Form)
     */
    public function createTraceability(int $seed_id)
    {
        $seed = Seed::findOrFail($seed_id);
        return view('admin.create_traceability', compact('seed'));
    }

    /**
     * ៩. មុខងារសម្រាប់រក្សាទុកព័ត៌មានតាមដានប្រភពដើមដាច់ដោយឡែកចូល Database
     */
    public function storeTraceability(Request $request, $seed_id)
    {
        $request->validate([
            'batch_number'     => 'required|string|max:255',
            'origin_kh'        => 'required|string|max:255',
            'germination_rate' => 'required|numeric|min:0|max:100',
            'harvest_date'     => 'required|string|max:255',
            'expiry_date'      => 'required|string|max:255',
            'farm_name'         => 'nullable|string|max:255',
            'soil_ph'           => 'nullable|string|max:50',
            'watering_schedule' => 'nullable|string|max:255',
            'cultivation_guide' => 'nullable|string',
        ]);

        SeedTraceability::create([
            'seed_id'          => $seed_id,
            'batch_number'     => $request->batch_number,
            'origin_kh'        => $request->origin_kh,
            'origin_en'        => $request->origin_en ?? 'Takream Village, Banan District, Battambang', // 🔒 ចាក់សោរសុវត្ថិភាព
            'germination_rate' => $request->germination_rate,
            'harvest_date'     => $request->harvest_date,
            'expiry_date'      => $request->expiry_date,
            'farm_name'         => $request->farm_name,
            'soil_ph'           => $request->soil_ph,
            'watering_schedule' => $request->watering_schedule,
            'cultivation_guide' => $request->cultivation_guide,
        ]);

        return redirect('/admin/seeds')->with('success', 'ព័ត៌មានតាមដានប្រភពត្រូវបានបន្ថែមដោយជោគជ័យ!');
    }

    /**
     * ១០. មុខងារផ្ទៀងផ្ទាត់ការកែប្រែព័ត៌មានវគ្គផលិតពីទំព័រ Traceability List មេ
     */
    public function updateTraceability(Request $request, $id)
    {
        $request->validate([
            'batch_number'      => 'required|string',
            'origin_kh'         => 'required|string',
            'germination_rate'  => 'required|numeric',
            'harvest_date'      => 'required|string|max:255',
            'expiry_date'       => 'required|string|max:255',
            'farm_name'         => 'nullable|string',
            'soil_ph'           => 'nullable|string',
            'watering_schedule' => 'nullable|string',
            'cultivation_guide' => 'nullable|string',
        ]);

        $trace = SeedTraceability::findOrFail($id);

        $trace->update([
            'batch_number'      => $request->batch_number,
            'origin_kh'         => $request->origin_kh,
            'origin_en'         => $trace->origin_en ?? 'Takream Village, Battambang',
            'germination_rate'  => $request->germination_rate,
            'harvest_date'      => $request->harvest_date,
            'expiry_date'       => $request->expiry_date,
            'farm_name'         => $request->farm_name,
            'soil_ph'           => $request->soil_ph,
            'watering_schedule' => $request->watering_schedule,
            'cultivation_guide' => $request->cultivation_guide,
        ]);

        return redirect()->back()->with('success', 'ព័ត៌មានវគ្គផលិតត្រូវបានកែប្រែដោយជោគជ័យ! 🌾');
    }

    /**
     * ១១. មុខងារសម្រាប់បង្ហាញទំព័រលម្អិតខ្សែសង្វាក់ផលិតកម្ម (Traceability Show)
     */
    public function showTraceability($id)
    {
        $trace = SeedTraceability::with('seed')->findOrFail($id);
        return view('admin.traceability_show', compact('trace'));
    }

    /**
     * ១២. មុខងារបង្ហាញបញ្ជីព័ត៌មានតាមដានប្រភពដើមទាំងអស់ទៅកាន់ Admin (Traceability List)
     */
    public function adminTraceability()
    {
        // $traceabilities = SeedTraceability::with('seed')->orderBy('created_at', 'desc')->get();
        // return view('admin.traceability', compact('traceabilities'));

        $traceabilities = SeedTraceability::with('seed')->orderBy('created_at', 'desc')->get();
        return view('admin.traceability', compact('traceabilities'));
    }

    /**
     * ១៣. មុខងារសម្រាប់លុបព័ត៌មានប្រភពដើម (Delete Traceability)
     */
    public function destroyTraceability($id)
    {
        $traceability = SeedTraceability::findOrFail($id);
        $traceability->delete();
        return redirect()->back()->with('success', 'ព័ត៌មានវគ្គផលិត (Batch) ត្រូវបានលុបចេញដោយជោគជ័យ!');
    }

    /**
     * ១៤. មុខងារសម្រាប់បន្ថែមគ្រាប់ពូជចូលក្នុងកន្ត្រកទំនិញ (Session Cart)
     */
    public function addToCart($id)
    {
        $seed = Seed::findOrFail($id);

        // ឆែកមើលមុន បើអស់ស្តុកហើយ មិនឱ្យដាក់ចូលកន្ត្រកឡើយ
        if ($seed->stock <= 0) {
            return redirect()->back()->with('error', 'សុំទោស! គ្រាប់ពូជនេះអស់ពីស្តុកហើយ។');
        }

        $cart = session()->get('cart', []);

        if(!isset($cart[$id])) {
            $cart[$id] = [
                "name" => $seed->name_kh,
                "quantity" => 1,
                "price" => $seed->price,
                "image" => $seed->image,
                "stock" => $seed->stock - 1 // ស្តុកដែលសល់បង្ហាញក្នុងកន្ត្រក
            ];

            // កាត់ស្តុកពិតប្រាកដនៅក្នុង Database ថយចុះ ១ កញ្ចប់ភ្លាមៗ
            $seed->decrement('stock', 1);
        } else {
            if ($seed->stock > 0) {
                $cart[$id]['quantity']++;

                // កាត់ស្តុកពិតប្រាកដនៅក្នុង Database ថយចុះ ១ កញ្ចប់បន្ថែម
                $seed->decrement('stock', 1);
            } else {
                return redirect()->back()->with('error', 'មិនអាចបន្ថែមបានទេ ព្រោះលើសចំនួនស្តុកដែលមាន!');
            }
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'គ្រាប់ពូជត្រូវបានដាក់ចូលកន្ត្រក និងកាត់ស្តុកដោយជោគជ័យ! 🛒');
    }

    /**
     * ១៥. មុខងារសម្រាប់បើកបង្ហាញទំព័រកន្ត្រកទំនិញ (View Cart)
     */
    public function showCart()
    {
        return view('cart');
    }

    /**
     * ១៦. មុខងារសម្រាប់ដំណើរការទូទាត់ និងរក្សាទុកការកម្ម៉ង់ (Checkout Logic)
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string',
        ]);

        $cart = session()->get('cart');
        if(!$cart) {
            return redirect()->back()->with('error', 'កន្ត្រកទំនិញរបស់អ្នកនៅទទេឡើយ!');
        }

        $totalPrice = 0;
        foreach($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // រក្សាទុកទៅក្នុងតារាង orders
        $order = \App\Models\Order::create([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'delivery_address' => $request->delivery_address,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        // រក្សាទុកទំនិញនិមួយៗទៅក្នុងតារាង order_items
        foreach($cart as $id => $details) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'seed_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
                'subtotal' => $details['price'] * $details['quantity']
            ]);

            // កាត់ស្តុកគ្រាប់ពូជចេញពីតារាង seeds ភ្លាមៗ
            // $seed = Seed::find($id);
            // if($seed) {
            //     $seed->decrement('stock', $details['quantity']);
            // }
        }

        session()->forget('cart');
        return redirect('/')->with('success', 'ការកម្ម៉ង់ទិញរបស់អ្នកទទួលបានជោគជ័យ! យើងនឹងទាក់ទងទៅអ្នកក្នុងពេលឆាប់ៗ។');
    }

    /**
     * ១៧. មុខងារសម្រាប់ផ្លាស់ប្តូរភាសាប្រព័ន្ធ (Localization)
     */
    public function changeLanguage($locale)
    {
        if (in_array($locale, ['kh', 'en'])) {
            session()->put('locale', $locale);
        }
        return redirect()->back();
    }

    /**
     * ១៨. មុខងារសម្រាប់កែប្រែចំនួនកញ្ចប់ក្នុងទំព័រកន្ត្រក (AJAX Update)
     */
    public function updateCart(Request $request)
    {
        if($request->id && isset($request->quantity)){
            $seed = Seed::find($request->id);
            $cart = session()->get('cart');

            $oldQty = $cart[$request->id]["quantity"];
            $newQty = intval($request->quantity);

            // គណនារកចំនួនខុសគ្នា រវាងចំនួនចាស់ និងចំនួនថ្មី
            if ($newQty > $oldQty) {
                $difference = $newQty - $oldQty;

                // បើកសិទ្ធិឆែកមើលស្តុកក្នុង DB មុន
                if ($seed->stock < $difference) {
                    return response()->json(['error' => 'សុំទោស! ចំនួនក្នុងស្តុកមិនគ្រប់គ្រាន់ឡើយ សល់ត្រឹមតែ ' . $seed->stock . ' កញ្ចប់ប៉ុណ្ណោះ។'], 400);
                }

                //  កែសម្រួល៖ បើថែមចំនួនក្នុងកន្ត្រក ត្រូវកាត់ស្តុកក្នុង Database ថយចុះថែម
                $seed->decrement('stock', $difference);
            } else if ($newQty < $oldQty) {
                $difference = $oldQty - $newQty;

                //  កែសម្រួល៖ បើដកចំនួនក្នុងកន្ត្រក ត្រូវបូកស្តុកចូល Database ត្រឡប់មកវិញ
                $seed->increment('stock', $difference);
            }

            $cart[$request->id]["quantity"] = $newQty;
            session()->put('cart', $cart);
            return response()->json(['success' => 'បានធ្វើបច្ចុប្បន្នភាពកន្ត្រក និងស្តុកជោគជ័យ!']);
        }
    }

    /**
     * ១៩. មុខងារសម្រាប់ដកទំនិញចេញពីកន្ត្រក (Remove Item)
     */
    public function removeFromCart(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');

            if(isset($cart[$request->id])) {
                $qtyToReturn = $cart[$request->id]["quantity"];

                //  កែសម្រួល៖ ទាញយកគ្រាប់ពូជនោះ រួចបូកចំនួនដែលធ្លាប់ទិញចូលស្តុក DB វិញទាំងអស់ការពារបាត់ទិន្នន័យ
                $seed = Seed::find($request->id);
                if ($seed) {
                    $seed->increment('stock', $qtyToReturn);
                }

                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'រកមិនឃើញទិន្នន័យគ្រាប់ពូជឡើយ'], 400);
    }

    /**
     * ២០. មុខងារសម្រាប់បង្ហាញបញ្ជីការកម្ម៉ង់ទិញទាំងអស់ទៅកាន់ Admin (Order List)
     */
    public function adminOrders()
    {
        $orders = \App\Models\Order::with('items.seed')->orderBy('created_at', 'desc')->get();
        return view('admin.orders', compact('orders'));
    }

    /**
     * ២១. មុខងារសម្រាប់ Admin ចុចប្ដូរស្ថានភាពការកម្ម៉ង់ (Update Status)
     */
    public function updateOrderStatus($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->update(['status' => 'completed']);
        return redirect()->back()->with('success', 'បានធ្វើបច្ចុប្បន្នភាពស្ថានភាពការកម្ម៉ង់រួចរាល់!');
    }

    /**
     * ២២. មុខងារសម្រាប់លុបវិក្កយបត្រកម្ម៉ង់ទិញ (Delete Order)
     */
    public function destroyOrder($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->items()->delete();
        $order->delete();
        return redirect()->back()->with('success', 'ការកម្ម៉ង់ទិញ ត្រូវបានលុបចេញពីប្រព័ន្ធហើយ!');
    }

    /**
     * ២៣. មុខងារសម្រាប់បង្ហាញទំព័រលម្អិតគ្រាប់ពូជចម្បង (Seed Detail Page)
     */
    public function seedDetail($id)
    {
        $seed = Seed::with(['category', 'traceability'])->findOrFail($id);
        return view('detail', compact('seed'));
    }

    /**
     * ២៤. មុខងារសម្រាប់បង្ហាញទំព័រ Dashboard និងគណនាទិន្នន័យស្ថិតិ Dynamic
     */
    public function adminDashboard()
    {
        $total_revenue = \App\Models\Order::sum('total_price');
        $pending_orders_count = \App\Models\Order::where('status', 'pending')->count();
        $total_seeds_count = Seed::count();
        $low_stock_count = Seed::where('stock', '<=', 5)->count();
        $recent_orders = \App\Models\Order::orderBy('created_at', 'desc')->take(5)->get();

        $bestSellers = DB::table('order_items')
            ->join('seeds', 'order_items.seed_id', '=', 'seeds.id')
            ->select('seeds.name_kh', 'seeds.image', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.seed_id', 'seeds.name_kh', 'seeds.image')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'total_revenue',
            'pending_orders_count',
            'total_seeds_count',
            'low_stock_count',
            'recent_orders',
            'bestSellers'
        ));
    }

    /**
     * ២៥. មុខងារសម្រាប់ស្វែងរកឈ្មោះគ្រាប់ពូជ (Search Seeds)
     */
    public function searchSeeds(Request $request)
    {
        $query = $request->input('search');
        $seeds = Seed::where('name_kh', 'LIKE', "%{$query}%")
                    ->orWhere('name_en', 'LIKE', "%{$query}%")
                    ->with('category')
                    ->get();

        return view('search', compact('seeds', 'query'));
    }

    /**
     * ២៦. មុខងារសម្រាប់តាមដានស្កេនលេខ Batch របស់កសិករ (Traceability Tracking Search)
     */
    public function traceBatch(Request $request)
    {
        $query = $request->input('search');
        $traceability = SeedTraceability::with('seed')
                            ->where('batch_number', 'LIKE', "%{$query}%")
                            ->first();

        if ($traceability) {
            return view('traceability_result', compact('traceability', 'query'));
        }

        return redirect()->back()->withErrors([
            'search' => 'មិនមានទិន្នន័យគ្រាប់ពូជដែលអ្នកកំពុងស្វែងរកឡើយ! សូមសាកល្បងពាក្យផ្សេងទៀត។',
        ])->withInput();
    }


    /**
     *  បន្ថែមថ្មី៖ មុខងារសម្រាប់បង្ហាញប្រវត្តិបញ្ជាទិញរបស់អតិថិជនម្នាក់ៗ (User Order History)
     */
    public function myOrders()
    {
        $user = Auth::user();

        $orders = \App\Models\Order::with('items.seed')
                    ->where('customer_name', $user->name)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // បោះទៅកាន់ទំព័រ my_orders.blade.php របស់អតិថិជន
        return view('my_orders', compact('orders'));
    }

}
