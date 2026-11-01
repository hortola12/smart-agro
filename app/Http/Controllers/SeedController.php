<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seed;
use App\Models\SeedReview;
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
    public function addToCart(Request $request, $id)
    {
        $seed = Seed::findOrFail($id);
        // ឆែកមើលមុន បើអស់ស្តុកហើយ មិនឱ្យដាក់ចូលកន្ត្រកឡើយ
        if ($seed->stock <= 0) {
            return redirect()->back()->with('error', 'សុំទោស! គ្រាប់ពូជនេះអស់ពីស្តុកហើយ។');
        }

        $quantityToAdd = max(1, (int) $request->input('quantity', 1));
        $cart = session()->get('cart', []);
        $currentQty = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;

        if ($currentQty + $quantityToAdd > $seed->stock) {
            return redirect()->back()->with('error', "មិនអាចបន្ថែមបានទេ ព្រោះលើសចំនួនស្តុកដែលមាន (ស្តុកនៅសល់ត្រឹមតែ {$seed->stock} កញ្ចប់)!");
        }

        if (! isset($cart[$id])) {
            $cart[$id] = [
                'name' => $seed->name_kh,
                'quantity' => $quantityToAdd,
                'price' => $seed->price,
                'image' => $seed->image,
                'stock' => $seed->stock,
            ];
        } else {
            $cart[$id]['quantity'] += $quantityToAdd;
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

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $appliedCoupon = session()->get('applied_coupon');
        $couponCode = null;
        $discountAmount = 0;
        $totalPrice = $subtotal;

        if ($appliedCoupon) {
            $coupon = Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->isValidForAmount($subtotal)) {
                $couponCode = $coupon->code;
                $discountAmount = $coupon->calculateDiscount($subtotal);
                $totalPrice = max(0, $subtotal - $discountAmount);
            }
        }

        try {
            DB::transaction(function () use ($request, $cart, $totalPrice, $couponCode, $discountAmount) {
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
                    'coupon_code' => $couponCode,
                    'discount_amount' => $discountAmount,
                    'status' => 'pending',
                ]);

                // ប្រសិនបើមានប្រើប្រាស់ Coupon ត្រូវបង្កើនចំនួនប្រើប្រាស់
                if ($couponCode) {
                    Coupon::where('code', $couponCode)->increment('used_count');
                }

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
            session()->forget('applied_coupon');

            return redirect('/my-orders')->with('success', 'ការកម្ម៉ង់ទិញរបស់អ្នកទទួលបានជោគជ័យ! លោកអ្នកអាចពិនិត្យស្ថានភាពដឹកជញ្ជូន ឬបោះពុម្ពវិក្កយបត្រនៅទីនេះ។');
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
     *  មុខងារសម្រាប់បង្ហាញទំព័រគ្រាប់ពូជពេញចិត្ត (Wishlist Page)
     */
    public function showWishlist()
    {
        $wishlistIds = array_keys(session()->get('wishlist', []));
        $seeds = Seed::with(['category', 'traceability', 'reviews'])
            ->whereIn('id', $wishlistIds)
            ->get();

        return view('wishlist', compact('seeds'));
    }

    /**
     *  មុខងារសម្រាប់បន្ថែម ឬដកគ្រាប់ពូជចេញពីបញ្ជីពេញចិត្ត (Toggle Wishlist)
     */
    public function toggleWishlist(Request $request, $id)
    {
        $seed = Seed::findOrFail($id);
        $wishlist = session()->get('wishlist', []);

        if (isset($wishlist[$id])) {
            unset($wishlist[$id]);
            $added = false;
            $message = "បានដក {$seed->name_kh} ចេញពីបញ្ជីពេញចិត្ត";
        } else {
            $wishlist[$id] = [
                'id' => $seed->id,
                'name' => $seed->name_kh,
                'price' => $seed->price,
                'image' => $seed->image,
                'added_at' => now()->toDateTimeString(),
            ];
            $added = true;
            $message = "បានរក្សាទុក {$seed->name_kh} ក្នុងបញ្ជីពេញចិត្ត! ❤️";
        }

        session()->put('wishlist', $wishlist);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'count' => count($wishlist),
                'message' => $message,
            ]);
        }

        return back()->with('wishlist_status', $message);
    }

    /**
     *  មុខងារសម្រាប់ផ្លាស់ប្តូរគ្រាប់ពូជពី Wishlist ទៅក្នុងកន្ត្រកទំនិញ (Move Wishlist to Cart)
     */
    public function moveWishlistToCart(Request $request, $id)
    {
        $seed = Seed::findOrFail($id);
        if ($seed->stock <= 0) {
            return back()->with('error', 'សុំទោស! គ្រាប់ពូជនេះអស់ពីស្តុកហើយ មិនអាចដាក់ចូលកន្ត្រកបានទេ។');
        }

        // បញ្ចូលទៅកន្ត្រកទំនិញ
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
                $cart[$id]['quantity'] += 1;
            }
        }
        session()->put('cart', $cart);

        // ដកចេញពី Wishlist
        $wishlist = session()->get('wishlist', []);
        unset($wishlist[$id]);
        session()->put('wishlist', $wishlist);

        return back()->with('success', "បានបញ្ចូល {$seed->name_kh} ទៅក្នុងកន្ត្រកទំនិញដោយជោគជ័យ! 🛒");
    }

    /**
     *  មុខងារសម្រាប់សម្អាតបញ្ជីពេញចិត្តទាំងអស់ (Clear All Wishlist)
     */
    public function clearWishlist()
    {
        session()->forget('wishlist');

        return back()->with('success', 'បានសម្អាតបញ្ជីគ្រាប់ពូជពេញចិត្តរួចរាល់!');
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

        // Date Filter (Today, This Week, This Month, or Custom Range)
        if ($request->filled('date_filter')) {
            if ($request->date_filter === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($request->date_filter === 'this_week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($request->date_filter === 'this_month') {
                $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(10)->withQueryString();

        // ស្ថិតិ KPI សង្ខេបសម្រាប់បង្ហាញលើផ្ទាំង Orders
        $totalOrdersCount = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_price');
        $pendingCount = Order::where('status', 'pending')->count();
        $completedCount = Order::where('status', 'completed')->count();

        return view('admin.orders', compact('orders', 'totalOrdersCount', 'totalRevenue', 'pendingCount', 'completedCount'));
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
        $seed = Seed::with(['category', 'traceability', 'reviews.user'])->findOrFail($id);

        return view('detail', compact('seed'));
    }

    /**
     *  បន្ថែមថ្មី៖ មុខងារចេញវិញ្ញាបនបត្រគុណភាព និងអត្រាដំណុះផ្លូវការ (Official Seed Quality Certificate A4)
     */
    public function seedCertificate($id)
    {
        $seed = Seed::with(['category', 'traceability'])->findOrFail($id);

        return view('seed_certificate', compact('seed'));
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

        // គ្រាប់ពូជដែលជិតអស់ពីស្តុក សម្រាប់បង្ហាញផ្ទាំង Alert & Restock លើ Dashboard
        $low_stock_seeds = Seed::with('category')->where('stock', '<=', 5)->orderBy('stock', 'asc')->get();

        // វគ្គផលិតគ្រាប់ពូជដែលជិតផុតកំណត់ក្នុងរយៈពេល ៦០ ថ្ងៃ (Expiring Batches)
        $expiringBatches = SeedTraceability::with('seed')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(60))
            ->orderBy('expiry_date', 'asc')
            ->get();
        $expiringBatchesCount = $expiringBatches->count();

        // ស្ថិតិចំណូល ៦ ខែចុងក្រោយសម្រាប់គូរ Visual Chart
        $monthlySales = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthStart = $monthDate->copy()->startOfMonth();
            $monthEnd = $monthDate->copy()->endOfMonth();
            $monthLabel = $monthStart->format('M Y');
            $revenue = Order::whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_price');
            $ordersCount = Order::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $monthlySales[] = [
                'month' => $monthLabel,
                'revenue' => round($revenue, 2),
                'orders' => $ordersCount,
            ];
        }

        // ចំណែកប្រភេទបន្លែសម្រាប់ Doughnut Chart
        $categoryStats = Category::withCount('seeds')->get();

        return view('admin.dashboard', compact(
            'total_revenue',
            'pending_orders_count',
            'total_seeds_count',
            'low_stock_count',
            'recent_orders',
            'bestSellers',
            'low_stock_seeds',
            'expiringBatches',
            'expiringBatchesCount',
            'monthlySales',
            'categoryStats'
        ));
    }

    /**
     * ២៥. មុខងារសម្រាប់ស្វែងរកឈ្មោះគ្រាប់ពូជ (Search Seeds) & Filter តាមប្រភេទ
     */
    public function searchSeeds(Request $request)
    {
        $query = $request->input('search');
        $categoryId = $request->input('category_id');

        $seedsQuery = Seed::with(['category', 'traceability']);

        if ($query) {
            $seedsQuery->where(function ($q) use ($query) {
                $q->where('name_kh', 'LIKE', "%{$query}%")
                    ->orWhere('name_en', 'LIKE', "%{$query}%")
                    ->orWhere('batch_number', 'LIKE', "%{$query}%");
            });
        }

        if ($categoryId && $categoryId !== 'all') {
            $seedsQuery->where('category_id', $categoryId);
        }

        $seeds = $seedsQuery->get();
        $selectedCategory = $categoryId ? Category::find($categoryId) : null;

        return view('search', compact('seeds', 'query', 'selectedCategory'));
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
     *  បន្ថែមថ្មី៖ មុខងារតាមដានការកម្ម៉ង់ទូទៅសម្រាប់កសិករ/អតិថិជន (Public Order Tracking by Phone or ID)
     */
    public function orderTracking(Request $request)
    {
        $search = trim($request->input('search', ''));
        $orders = collect();

        if ($search !== '') {
            $cleanSearch = preg_replace('/^#?ORD-0*/i', '', $search);

            $orders = Order::with('items.seed')
                ->where(function ($q) use ($search, $cleanSearch) {
                    $q->where('customer_phone', 'LIKE', "%{$search}%")
                        ->orWhere('customer_name', 'LIKE', "%{$search}%");

                    if (is_numeric($cleanSearch)) {
                        $q->orWhere('id', (int) $cleanSearch);
                    }
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('order_tracking', compact('orders', 'search'));
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

    /**
     * ៣២. មុខងារបន្ថែមស្តុកគ្រាប់ពូជបន្ទាន់ (Quick Restock)
     */
    public function quickRestock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $seed = Seed::findOrFail($id);
        $seed->increment('stock', $request->quantity);

        return redirect()->back()->with('success', "បានបន្ថែមស្តុកគ្រាប់ពូជ '{$seed->name_kh}' ចំនួន +{$request->quantity} កញ្ចប់ជោគជ័យ! ស្តុកសរុបបច្ចុប្បន្ន៖ {$seed->stock}");
    }

    /**
     * ៣៣. មុខងារទាញយករបាយការណ៍ការកម្ម៉ង់ជាទម្រង់ CSV (Export Orders CSV)
     */
    public function exportOrdersCsv()
    {
        $orders = Order::with('items.seed')->orderBy('created_at', 'desc')->get();
        $filename = 'orders-report-'.date('Y-m-d').'.csv';

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Microsoft Excel renders Khmer text properly
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Order ID',
                'Date',
                'Customer Name',
                'Customer Phone',
                'Delivery Address',
                'Status',
                'Total Amount ($)',
                'Items Purchased',
            ]);

            foreach ($orders as $order) {
                $itemsSummary = $order->items->map(function ($item) {
                    return ($item->seed ? $item->seed->name_kh : 'Seed').' (x'.$item->quantity.')';
                })->implode('; ');

                fputcsv($handle, [
                    '#ORD-'.str_pad($order->id, 5, '0', STR_PAD_LEFT),
                    $order->created_at ? $order->created_at->format('Y-m-d H:i') : '-',
                    $order->customer_name,
                    $order->customer_phone,
                    $order->delivery_address,
                    ucfirst($order->status),
                    number_format($order->total_price, 2),
                    $itemsSummary,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * ៣៤. មុខងារទាញយកទិន្នន័យស្តុកគ្រាប់ពូជជាទម្រង់ CSV (Export Seeds Inventory CSV)
     */
    public function exportSeedsCsv()
    {
        $seeds = Seed::with(['category', 'traceability'])->orderBy('name_en', 'asc')->get();
        $filename = 'seeds-inventory-'.date('Y-m-d').'.csv';

        $callback = function () use ($seeds) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID',
                'Name (KH)',
                'Name (EN)',
                'Category',
                'Price ($)',
                'Stock Available',
                'Batch Number',
                'Germination Rate (%)',
                'Harvest Date',
                'Expiry Date',
            ]);

            foreach ($seeds as $seed) {
                fputcsv($handle, [
                    $seed->id,
                    $seed->name_kh,
                    $seed->name_en,
                    $seed->category?->name_kh ?? 'N/A',
                    number_format($seed->price, 2),
                    $seed->stock,
                    $seed->batch_number ?? '-',
                    $seed->traceability?->germination_rate ? $seed->traceability->germination_rate.'%' : 'N/A',
                    $seed->traceability?->harvest_date ?? '-',
                    $seed->traceability?->expiry_date ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * ៣៥. មុខងារបោះពុម្ពស្លាក Sticker គ្រាប់ពូជជាសន្លឹក (Print Packaging Labels Sheet)
     */
    public function printLabels(Request $request, $id)
    {
        $seed = Seed::with(['category', 'traceability'])->findOrFail($id);
        $count = max(1, min(24, (int) $request->input('count', 8)));

        return view('admin.print_labels', compact('seed', 'count'));
    }

    /**
     * ៣៦. មុខងារបោះពុម្ពបញ្ជីរៀបចំទំនិញរួមប្រចាំថ្ងៃ (Daily Bulk Shipping & Packing Manifest)
     */
    public function packingManifest(Request $request)
    {
        $status = $request->input('status', 'active');
        $query = Order::with('items.seed')->orderBy('id', 'asc');

        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'processing') {
            $query->where('status', 'processing');
        } else {
            // Default active orders to pack and ship
            $query->whereIn('status', ['pending', 'processing', 'shipping']);
        }

        $orders = $query->get();

        // Aggregate total seed quantities
        $seedSummary = [];
        $totalPacks = 0;
        $totalRevenueToCollect = 0;

        foreach ($orders as $order) {
            $totalRevenueToCollect += $order->total_price;
            foreach ($order->items as $item) {
                $seedId = $item->seed_id;
                $seedName = $item->seed?->name_kh ?? 'គ្រាប់ពូជលុបចេញ';
                $batchNumber = $item->seed?->batch_number ?? '---';
                $image = $item->seed?->image;
                $totalPacks += $item->quantity;

                if (! isset($seedSummary[$seedId])) {
                    $seedSummary[$seedId] = [
                        'name' => $seedName,
                        'batch' => $batchNumber,
                        'image' => $image,
                        'quantity' => 0,
                    ];
                }
                $seedSummary[$seedId]['quantity'] += $item->quantity;
            }
        }

        return view('admin.packing_manifest', compact('orders', 'seedSummary', 'totalPacks', 'totalRevenueToCollect', 'status'));
    }

    /**
     *  មុខងារសម្រាប់អតិថិជនដាក់មតិយោបល់ និងវាយតម្លៃផ្កាយ (Store Review & Rating)
     */
    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $seed = Seed::findOrFail($id);

        SeedReview::create([
            'seed_id' => $seed->id,
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'rating' => (int) $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        return back()->with('review_success', 'សូមអរគុណសម្រាប់ការវាយតម្លៃ! មតិយោបល់របស់អ្នកត្រូវបានកត់ត្រាជោគជ័យ។ ⭐');
    }

    /**
     *  មុខងារគ្រប់គ្រងមតិយោបល់ និងការវាយតម្លៃសម្រាប់ Admin (Admin Reviews Index)
     */
    public function adminReviews(Request $request)
    {
        $query = SeedReview::with('seed')->latest();

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('comment', 'like', "%{$search}%")
                    ->orWhereHas('seed', function ($sq) use ($search) {
                        $sq->where('name_kh', 'like', "%{$search}%")
                            ->orWhere('name_en', 'like', "%{$search}%");
                    });
            });
        }

        $reviews = $query->paginate(15);
        $totalReviews = SeedReview::count();
        $avgRating = SeedReview::avg('rating') ? round(SeedReview::avg('rating'), 1) : 5.0;

        return view('admin.reviews', compact('reviews', 'totalReviews', 'avgRating'));
    }

    /**
     *  មុខងារលុបមតិយោបល់មិនសមរម្យចេញពីប្រព័ន្ធ (Admin Delete Review)
     */
    public function destroyReview($id)
    {
        $review = SeedReview::findOrFail($id);
        $review->delete();

        return back()->with('success', 'បានលុបមតិយោបល់អតិថិជនដោយជោគជ័យ! 🗑️');
    }

    /**
     *  មុខងារអនុវត្តលេខកូដបញ្ចុះតម្លៃក្នុងកន្ត្រកទំនិញ (Apply Coupon in Cart)
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('coupon_error', 'កន្ត្រកទំនិញរបស់អ្នកនៅទទេឡើយ!');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon || ! $coupon->is_active) {
            return back()->with('coupon_error', 'លេខកូដបញ្ចុះតម្លៃមិនត្រឹមត្រូវ ឬត្រូវបានបិទដំណើរការ!');
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast() && ! $coupon->expires_at->isToday()) {
            return back()->with('coupon_error', 'លេខកូដបញ្ចុះតម្លៃនេះបានផុតកំណត់ហើយ!');
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return back()->with('coupon_error', 'លេខកូដបញ្ចុះតម្លៃនេះត្រូវបានប្រើប្រាស់អស់កូតាកំណត់ហើយ!');
        }

        if ($subtotal < $coupon->min_order_amount) {
            return back()->with('coupon_error', 'ការកម្ម៉ង់ត្រូវមានតម្លៃយ៉ាងតិច $'.number_format($coupon->min_order_amount, 2).' ទើបអាចប្រើលេខកូដនេះបាន!');
        }

        $discount = $coupon->calculateDiscount($subtotal);

        session()->put('applied_coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'description' => $coupon->description,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'discount_amount' => $discount,
        ]);

        return back()->with('coupon_success', "បានអនុវត្តលេខកូដ '{$coupon->code}' ជោគជ័យ! បញ្ចុះតម្លៃបាន $".number_format($discount, 2).' 🎉');
    }

    /**
     *  មុខងារដកលេខកូដបញ្ចុះតម្លៃចេញពីកន្ត្រក (Remove Coupon)
     */
    public function removeCoupon()
    {
        session()->forget('applied_coupon');

        return back()->with('coupon_success', 'បានដកលេខកូដបញ្ចុះតម្លៃចេញវិញរួចរាល់!');
    }

    /**
     *  មុខងារគ្រប់គ្រងលេខកូដបញ្ចុះតម្លៃសម្រាប់ Admin (Admin Coupons Index)
     */
    public function adminCoupons(Request $request)
    {
        $query = Coupon::orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $coupons = $query->paginate(15);
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('is_active', true)->count();

        return view('admin.coupons', compact('coupons', 'totalCoupons', 'activeCoupons'));
    }

    /**
     *  មុខងារបង្កើតលេខកូដបញ្ចុះតម្លៃថ្មី (Admin Store Coupon)
     */
    public function adminStoreCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        Coupon::create([
            'code' => strtoupper(trim($request->code)),
            'description' => $request->description,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_discount_amount' => $request->max_discount_amount,
            'usage_limit' => $request->usage_limit,
            'expires_at' => $request->expires_at,
            'is_active' => true,
        ]);

        return back()->with('success', 'បានបង្កើតលេខកូដបញ្ចុះតម្លៃថ្មីដោយជោគជ័យ! 🏷️');
    }

    /**
     *  មុខងារបើក/បិទ ដំណើរការលេខកូដបញ្ចុះតម្លៃ (Admin Toggle Coupon)
     */
    public function adminToggleCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->is_active = ! $coupon->is_active;
        $coupon->save();

        $statusText = $coupon->is_active ? 'បានបើកដំណើរការ' : 'បានបិទដំណើរការ';

        return back()->with('success', "{$statusText} លេខកូដ '{$coupon->code}' ដោយជោគជ័យ!");
    }

    /**
     *  មុខងារលុបលេខកូដបញ្ចុះតម្លៃ (Admin Destroy Coupon)
     */
    public function adminDestroyCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return back()->with('success', 'បានលុបលេខកូដបញ្ចុះតម្លៃរួចរាល់! 🗑️');
    }
}
