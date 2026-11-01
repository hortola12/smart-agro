<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seed;
use App\Models\SeedTraceability;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeedController extends Controller
{
    /**
     * ១. មុខងារសម្រាប់បើកបង្ហាញ Form បន្ថែមគ្រាប់ពូជថ្មី
     */
    public function create()
    {
        // ទាញយកប្រភេទបន្លែទាំងអស់ពី Database តាមរយៈ Eloquent Model
        $categories = Category::all();

        return view('admin.create_seed', compact('categories'));
    }

    /**
     * ២. មុខងារសម្រាប់ចាប់ទិន្នន័យពី Form ទៅរក្សាទុកក្នុង Database (Store)
     */
    public function store(Request $request)
    {
        // ផ្ទៀងផ្ទាត់ទិន្នន័យពី Form បង្កើតថ្មី (Validation) ឱ្យវាស្គាល់ Fields ទាំងអស់រួមទាំងព័ត៌មានកសិកម្មលម្អិត
        $request->validate([
            'name_kh' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // ផ្ទៀងផ្ទាត់ដុំកូដវគ្គផលិតកម្ម (Traceability)
            'batch_number' => 'required|string|unique:seed_traceabilities,batch_number',
            'origin_kh' => 'required|string|max:255',
            'germination_rate' => 'required|numeric|min:0|max:100',
            'harvest_date' => 'required|string|max:255',
            'expiry_date' => 'required|string|max:255',
            'farm_name' => 'nullable|string|max:255',
            'soil_ph' => 'nullable|string|max:50',
            'watering_schedule' => 'nullable|string|max:255',
            'cultivation_guide' => 'nullable|string',
        ]);

        // កូដសម្រាប់ Upload រូបភាព
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/seeds'), $imageName);
        }

        // បង្កើតគ្រាប់ពូជចូលទៅក្នុងតារាង `seeds` មុនគេបង្អស់
        $seed = Seed::create([
            'category_id' => $request->category_id,
            'name_kh' => $request->name_kh,
            'name_en' => $request->name_en,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName,
            'batch_number' => $request->batch_number, // ភ្ជាប់លេខបាច់ទៅតារាងគ្រាប់ពូជផ្ទាល់
        ]);

        // បង្កើតទិន្នន័យវគ្គផលិតចូលតារាង seed_traceabilities ព្រមគ្នា ដោយចាក់សោរការពារ 'origin_en'
        SeedTraceability::create([
            'seed_id' => $seed->id, // 🔗 ភ្ជាប់ជាមួយ ID គ្រាប់ពូជដែលទើបតែបង្កើតខាងលើ
            'batch_number' => $request->batch_number,
            'origin_kh' => $request->origin_kh,

            //  ចាក់សោរសុវត្ថិភាពខ្ពស់បំផុត៖ ការពារកំហុស "origin_en doesn't have a default value"
            'origin_en' => $request->origin_en ?? ($request->origin_kh ?? 'Takream Village, Banan District, Battambang'),

            'germination_rate' => $request->germination_rate,
            'harvest_date' => $request->harvest_date,
            'expiry_date' => $request->expiry_date,
            'farm_name' => $request->farm_name,
            'soil_ph' => $request->soil_ph,
            'watering_schedule' => $request->watering_schedule,
            'cultivation_guide' => $request->cultivation_guide,
        ]);

        return redirect('/admin/seeds')->with('success', 'បានបន្ថែមគ្រាប់ពូជថ្មី និងបង្កើតខ្សែសង្វាក់ផលិតកម្មដោយជោគជ័យ! 🌾');
    }

    /**
     * ៣. មុខងារសម្រាប់ទាញទិន្នន័យគ្រាប់ពូជទាំងអស់មកបង្ហាញក្នុងតារាង Admin
     */
    public function index(Request $request)
    {
        $query = Seed::with('category')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_kh', 'LIKE', "%{$search}%")
                    ->orWhere('name_en', 'LIKE', "%{$search}%")
                    ->orWhere('batch_number', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        $seeds = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.seeds', compact('seeds', 'categories'));
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
        $categories = Category::all(); //  ថែមទាញ Categories ចូលទៅក្នុង Form Edit ការពារកំហុសទទេ

        return view('admin.edit_seed', compact('seed', 'categories'));
    }

    /**
     * ៦. មុខងារសម្រាប់ទទួលទិន្នន័យថ្មីទៅកែប្រែក្នុង Database (Update Logic)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name_kh' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|integer',
            'batch_number' => 'required|string',
            'origin_kh' => 'required|string|max:255',
            'germination_rate' => 'required|numeric|min:0|max:100',
            'harvest_date' => 'required|string|max:255',
            'expiry_date' => 'required|string|max:255',
            'farm_name' => 'nullable|string|max:255',
            'soil_ph' => 'nullable|string|max:50',
            'watering_schedule' => 'nullable|string|max:255',
            'cultivation_guide' => 'nullable|string',
        ]);

        $seed = Seed::findOrFail($id);

        // កូដគ្រប់គ្រងរូបភាព
        $imageName = $seed->image;
        if ($request->hasFile('image')) {
            // លុបរូបភាពចាស់ចេញ (បើមាន)
            if ($seed->image && file_exists(public_path('uploads/seeds/'.$seed->image))) {
                unlink(public_path('uploads/seeds/'.$seed->image));
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/seeds'), $imageName);
        }

        // កែប្រែទិន្នន័យក្នុងតារាង seeds
        $seed->update([
            'category_id' => $request->category_id,
            'name_kh' => $request->name_kh,
            'name_en' => $request->name_en,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName,
            'batch_number' => $request->batch_number,
        ]);

        SeedTraceability::updateOrCreate(
            ['seed_id' => $seed->id],
            [
                'batch_number' => $request->batch_number,
                'origin_kh' => $request->origin_kh,

                // ប្រព័ន្ធ Fallback ការពារតម្លៃទទេដាច់ខាត ១០០%
                'origin_en' => $seed->traceability?->origin_en ?? ($request->origin_en ?? 'Takream Village, Banan District, Battambang'),

                'germination_rate' => $request->germination_rate,
                'harvest_date' => $request->harvest_date,
                'expiry_date' => $request->expiry_date,
                'farm_name' => $request->farm_name,
                'soil_ph' => $request->soil_ph,
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
        $hasOrders = OrderItem::where('seed_id', $id)->exists();
        if ($hasOrders) {
            return redirect()->back()->with('error', 'មិនអាចលុបគ្រាប់ពូជនេះបានទេ! ព្រោះវាមានជាប់ជំពាក់នៅក្នុងប្រវត្តិកម្ម៉ង់ទិញរបស់អតិថិជន។');
        }

        // លុបព័ត៌មានតាមដានប្រភពដែលជាកូនចោលមុន (បើមាន) ដើម្បីកុំឱ្យទាស់ Foreign Key
        SeedTraceability::where('seed_id', $id)->delete();

        // លុបរូបភាពចេញពីម៉ាស៊ីន
        if ($seed->image && file_exists(public_path('uploads/seeds/'.$seed->image))) {
            unlink(public_path('uploads/seeds/'.$seed->image));
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
            'batch_number' => 'required|string|max:255',
            'origin_kh' => 'required|string|max:255',
            'germination_rate' => 'required|numeric|min:0|max:100',
            'harvest_date' => 'required|string|max:255',
            'expiry_date' => 'required|string|max:255',
            'farm_name' => 'nullable|string|max:255',
            'soil_ph' => 'nullable|string|max:50',
            'watering_schedule' => 'nullable|string|max:255',
            'cultivation_guide' => 'nullable|string',
        ]);

        SeedTraceability::create([
            'seed_id' => $seed_id,
            'batch_number' => $request->batch_number,
            'origin_kh' => $request->origin_kh,
            'origin_en' => $request->origin_en ?? 'Takream Village, Banan District, Battambang', // 🔒 ចាក់សោរសុវត្ថិភាព
            'germination_rate' => $request->germination_rate,
            'harvest_date' => $request->harvest_date,
            'expiry_date' => $request->expiry_date,
            'farm_name' => $request->farm_name,
            'soil_ph' => $request->soil_ph,
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
            'batch_number' => 'required|string',
            'origin_kh' => 'required|string',
            'germination_rate' => 'required|numeric',
            'harvest_date' => 'required|string|max:255',
            'expiry_date' => 'required|string|max:255',
            'farm_name' => 'nullable|string',
            'soil_ph' => 'nullable|string',
            'watering_schedule' => 'nullable|string',
            'cultivation_guide' => 'nullable|string',
        ]);

        $trace = SeedTraceability::findOrFail($id);

        $trace->update([
            'batch_number' => $request->batch_number,
            'origin_kh' => $request->origin_kh,
            'origin_en' => $trace->origin_en ?? 'Takream Village, Battambang',
            'germination_rate' => $request->germination_rate,
            'harvest_date' => $request->harvest_date,
            'expiry_date' => $request->expiry_date,
            'farm_name' => $request->farm_name,
            'soil_ph' => $request->soil_ph,
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
        $traceabilities = SeedTraceability::with('seed')->orderBy('created_at', 'desc')->paginate(10);

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

    // ១៤. មុខងារសម្រាប់បន្ថែមគ្រាប់ពូជចូលក្នុងកន្ត្រកទំនិញ (Session Cart)
    public function addToCart($id)
    {
        $seed = Seed::findOrFail($id);
        // ឆែកមើលមុន បើអស់ស្តុកហើយ មិនឱ្យដាក់ចូលកន្ត្រកឡើយ
        if ($seed->stock <= 0) {
            return redirect()->back()->with('error', 'សុំទោស! គ្រាប់ពូជនេះអស់ពីស្តុកហើយ។');
        }
        $cart = session()->get('cart', []);
        if (! isset($cart[$id])) {
            $cart[$id] = [
                'name' => $seed->name_kh,
                'quantity' => 1,
                'price' => $seed->price,
                'image' => $seed->image,
                'stock' => $seed->stock,
            ];
        } else {
            if ($cart[$id]['quantity'] < $seed->stock) {
                $cart[$id]['quantity']++;
            } else {
                return redirect()->back()->with('error', 'មិនអាចបន្ថែមបានទេ ព្រោះលើសចំនួនស្តុកដែលមាន!');
            }
        }
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'គ្រាប់ពូជត្រូវបានដាក់ចូលកន្ត្រកដោយជោគជ័យ! 🛒');
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
        if (empty($cart)) {
            return redirect()->back()->with('error', 'កន្ត្រកទំនិញរបស់អ្នកនៅទទេឡើយ!');
        }

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        try {
            DB::transaction(function () use ($request, $cart, $totalPrice) {
                // ១. ចាក់សោរ និងកាត់ស្តុកពិតប្រាកដក្នុង Database
                foreach ($cart as $id => $details) {
                    $seed = Seed::where('id', $id)->lockForUpdate()->first();
                    if (! $seed || $seed->stock < $details['quantity']) {
                        throw new \Exception('សុំទោស! គ្រាប់ពូជ '.($seed->name_kh ?? '').' មិនមានចំនួនគ្រប់គ្រាន់ក្នុងស្តុកឡើយ។');
                    }
                    $seed->decrement('stock', $details['quantity']);
                }

                // ២. រក្សាទុកទៅក្នុងតារាង orders
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'delivery_address' => $request->delivery_address,
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                ]);

                // ៣. រក្សាទុកទំនិញនិមួយៗទៅក្នុងតារាង order_items
                foreach ($cart as $id => $details) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'seed_id' => $id,
                        'quantity' => $details['quantity'],
                        'price' => $details['price'],
                        'subtotal' => $details['price'] * $details['quantity'],
                    ]);
                }
            });

            session()->forget('cart');

            return redirect('/')->with('success', 'ការកម្ម៉ង់ទិញរបស់អ្នកទទួលបានជោគជ័យ! យើងនឹងទាក់ទងទៅអ្នកក្នុងពេលឆាប់ៗ។');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage() ?: 'មានបញ្ហាបច្ចេកទេសក្នុងការរក្សាទុកការបញ្ជាទិញ សូមព្យាយាមម្តងទៀត!');
        }
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
        if ($request->id && isset($request->quantity)) {
            $seed = Seed::find($request->id);
            $cart = session()->get('cart', []);
            $newQty = intval($request->quantity);

            if ($seed && $newQty > $seed->stock) {
                return response()->json(['error' => 'សុំទោស! ចំនួនក្នុងស្តុកមិនគ្រប់គ្រាន់ឡើយ សល់ត្រឹមតែ '.$seed->stock.' កញ្ចប់ប៉ុណ្ណោះ។'], 400);
            }

            if (isset($cart[$request->id])) {
                $cart[$request->id]['quantity'] = max(1, $newQty);
                session()->put('cart', $cart);

                return response()->json(['success' => 'បានធ្វើបច្ចុប្បន្នភាពកន្ត្រកជោគជ័យ!']);
            }
        }

        return response()->json(['error' => 'ទិន្នន័យមិនត្រឹមត្រូវ'], 400);
    }

    /**
     * ១៩. មុខងារសម្រាប់ដកទំនិញចេញពីកន្ត្រក (Remove Item)
     */
    public function removeFromCart(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart', []);

            if (isset($cart[$request->id])) {
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
    public function adminOrders(Request $request)
    {
        $query = Order::with('items.seed')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                    ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders', compact('orders'));
    }

    /**
     * ២១. មុខងារសម្រាប់ Admin ចុចប្ដូរស្ថានភាពការកម្ម៉ង់ (Update Status)
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $newStatus = $request->input('status', 'completed');
        $validStatuses = ['pending', 'processing', 'shipping', 'completed', 'cancelled'];

        if (! in_array($newStatus, $validStatuses)) {
            $newStatus = 'completed';
        }

        $order = Order::with('items.seed')->findOrFail($id);
        $oldStatus = $order->status;

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            // ប្រសិនបើបោះបង់ការកម្ម៉ង់ ត្រូវបូកស្តុកចូល Database វិញ
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->seed) {
                        $item->seed->increment('stock', $item->quantity);
                    }
                }
            } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                // ប្រសិនបើដំណើរការឡើងវិញ ត្រូវកាត់ស្តុកចេញវិញ
                foreach ($order->items as $item) {
                    if ($item->seed) {
                        $item->seed->decrement('stock', $item->quantity);
                    }
                }
            }

            $order->update(['status' => $newStatus]);
        });

        return redirect()->back()->with('success', 'បានធ្វើបច្ចុប្បន្នភាពស្ថានភាពការកម្ម៉ង់រួចរាល់!');
    }

    /**
     * ២២. មុខងារសម្រាប់លុបវិក្កយបត្រកម្ម៉ង់ទិញ (Delete Order)
     */
    public function destroyOrder($id)
    {
        $order = Order::with('items.seed')->findOrFail($id);

        DB::transaction(function () use ($order) {
            // បូកស្តុកត្រឡប់ចូលវិញប្រសិនបើការកម្ម៉ង់មិនទាន់បានបោះបង់ពីមុន
            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->seed) {
                        $item->seed->increment('stock', $item->quantity);
                    }
                }
            }
            $order->items()->delete();
            $order->delete();
        });

        return redirect()->back()->with('success', 'ការកម្ម៉ង់ទិញ ត្រូវបានលុបចេញពីប្រព័ន្ធ និងបូកស្តុកត្រឡប់មកវិញជោគជ័យ!');
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
        $total_revenue = Order::sum('total_price');
        $pending_orders_count = Order::where('status', 'pending')->count();
        $total_seeds_count = Seed::count();
        $low_stock_count = Seed::where('stock', '<=', 5)->count();
        $recent_orders = Order::orderBy('created_at', 'desc')->take(5)->get();

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

    // ២៦. មុខងារសម្រាប់តាមដានស្កេនលេខ Batch របស់កសិករ (Traceability Tracking Search)
    public function traceBatch(Request $request)
    {
        $query = $request->input('search') ?? $request->input('batch_number');
        if (! $query) {
            return redirect('/traceability');
        }
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

        $orders = Order::with('items.seed')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->whereNull('user_id')
                            ->where('customer_name', $user->name);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // បោះទៅកាន់ទំព័រ my_orders.blade.php របស់អតិថិជន
        return view('my_orders', compact('orders'));
    }

    /**
     * ២៧. មុខងារបង្ហាញបញ្ជីប្រភេទបន្លែទាំងអស់ (Admin Categories)
     */
    public function adminCategories(Request $request)
    {
        $query = Category::withCount('seeds')->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_kh', 'LIKE', "%{$search}%")
                    ->orWhere('name_en', 'LIKE', "%{$search}%");
            });
        }

        $categories = $query->paginate(10)->withQueryString();

        return view('admin.categories', compact('categories'));
    }

    /**
     * ២៨. មុខងាររក្សាទុកប្រភេទបន្លែថ្មី (Store Category)
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name_kh' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Category::create($validated);

        return redirect()->back()->with('success', 'បានបង្កើតប្រភេទបន្លែថ្មីដោយជោគជ័យ!');
    }

    /**
     * ២៩. មុខងារកែប្រែព័ត៌មានប្រភេទបន្លែ (Update Category)
     */
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name_kh' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return redirect()->back()->with('success', 'បានកែប្រែព័ត៌មានប្រភេទបន្លែដោយជោគជ័យ!');
    }

    /**
     * ៣០. មុខងារលុបប្រភេទបន្លែ (Delete Category)
     */
    public function destroyCategory($id)
    {
        $category = Category::withCount('seeds')->findOrFail($id);

        if ($category->seeds_count > 0) {
            return redirect()->back()->with('error', "មិនអាចលុបប្រភេទបន្លែ '{$category->name_kh}' បានទេ ព្រោះមានគ្រាប់ពូជចំនួន {$category->seeds_count} កំពុងប្រើប្រាស់វា!");
        }

        $category->delete();

        return redirect()->back()->with('success', 'បានលុបប្រភេទបន្លែដោយជោគជ័យ!');
    }

    /**
     * ៣១. មុខងារបង្ហាញបញ្ជីអតិថិជនទាំងអស់ (Admin Customers)
     */
    public function adminCustomers(Request $request)
    {
        $query = User::withCount('orders')
            ->withSum('orders', 'total_price')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        $customers = $query->paginate(10)->withQueryString();

        $totalUsersCount = User::count();
        $totalCustomersCount = User::where('role', 'customer')->count();
        $totalOrdersCount = Order::count();

        return view('admin.customers', compact('customers', 'totalUsersCount', 'totalCustomersCount', 'totalOrdersCount'));
    }
}
