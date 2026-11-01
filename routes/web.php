<?php

use App\Http\Controllers\SeedController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Category;
use App\Models\Seed;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/* ==========================================================================
    FRONTEND / PUBLIC ROUTES (អ្នកណាក៏អាចចូលមើលបាន - មិនបាច់ Login)
   ========================================================================== */

// ១. ទំព័រដើមគេហទំព័រ Frontend (Homepage)
Route::get('/', function () {
    // Eager Load 'traceability' និងរាប់ចំនួនគ្រាប់ពូជក្នុងប្រភេទនីមួយៗ (withCount)
    $categories = Category::withCount('seeds')->get();
    $seeds = Seed::with(['category', 'traceability'])->get();

    return view('home', compact('seeds', 'categories'));
});

// ២. ទំព័រព័ត៌មានលម្អិតគ្រាប់ពូជ
Route::get('/seed-detail/{id}', [SeedController::class, 'seedDetail']);

//  កែសម្រួល៖ ប្តូរឱ្យទៅជា /change-language/ ឱ្យត្រូវគ្នា ១០០% ជាមួយប៊ូតុងប្តូរភាសា KH/EN លើរបារ Navbar
Route::get('/change-language/{locale}', [SeedController::class, 'changeLanguage']);

//  ផ្លូវសម្រាប់ស្វែងរកឈ្មោះគ្រាប់ពូជទូទៅ (Search Vegetable Seeds)
Route::get('/search', [SeedController::class, 'searchSeeds']);

// ផ្លូវសម្រាប់តាមដានលេខបាច់ (QR Code / Direct URL)
Route::get('/trace', [SeedController::class, 'traceBatch']);
Route::get('/trace-batch', [SeedController::class, 'traceBatch']);

// ៣. ទំព័រ Form សម្រាប់ឱ្យកសិករវាយបញ្ចូលលេខ Batch ស្វែងរក
Route::get('/traceability', function () {
    return view('traceability_search');
});

// ៤. ទំព័រតាមដានស្ថានភាពការកម្ម៉ង់ទូទៅ (Public Order Tracking)
Route::get('/order-tracking', [SeedController::class, 'orderTracking'])->name('order.tracking');

/* ==========================================================================
   🛒 PUBLIC CART SESSION ROUTES (ប្រព័ន្ធកន្ត្រកទំនិញទូទៅ)
   ========================================================================== */
Route::get('/cart', [SeedController::class, 'showCart']);
Route::post('/add-to-cart/{id}', [SeedController::class, 'addToCart']);
Route::patch('/update-cart', [SeedController::class, 'updateCart']);
Route::delete('/remove-from-cart', [SeedController::class, 'removeFromCart']);

/* ==========================================================================
    CUSTOM AUTHENTICATION ROUTES (ប្រព័ន្ធ Login / Register / Logout)
   ========================================================================== */

// ១. ទំព័រចូលប្រើប្រាស់ (Login)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->has('remember'))) {
        $request->session()->regenerate();

        return Auth::user()->role === 'admin' ? redirect()->intended('/admin/dashboard') : redirect()->intended('/');
    }

    return back()->withErrors(['email' => 'អ៊ីមែល ឬលេខកូដសម្ងាត់របស់អ្នកមិនត្រឹមត្រូវឡើយ។'])->onlyInput('email');
})->middleware('throttle:5,1');

// ២. ទំព័រចុះឈ្មោះសមាជិកថ្មី (Register)
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'customer',
    ]);

    Auth::login($user);

    return redirect('/');
});

// ៣. ប៊ូតុងចាកចេញពីប្រព័ន្ធ (Logout - ដំណើរការប្រភេទ POST សុវត្ថិភាពខ្ពស់)
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

/* ==========================================================================
   🛒 PROTECTED USER ROUTES (លុះត្រាតែ Login រួចរាល់ទើបអាចចូលបញ្ជាទិញបាន)
   ========================================================================== */

Route::middleware(['auth'])->group(function () {

    // ផ្លូវបើកទំព័រត្រួតពិនិត្យវិក្កយបត្រជំហានចុងក្រោយ (GET)
    Route::get('/checkout', function () {
        return view('checkout');
    })->name('checkout');

    // ផ្លូវ Submit Form ដឹកជញ្ជូន រក្សាទុកការទិញដូរ កាត់ស្តុក និងសម្អាតកន្ត្រក (POST)
    Route::post('/checkout', [SeedController::class, 'checkout']);

    // ផ្លូវចូលមើលប្រវត្តិបញ្ជាទិញផ្ទាល់ខ្លួនរបស់កសិករ/អតិថិជន
    Route::get('/my-orders', [SeedController::class, 'myOrders'])->name('my.orders');
});

/* ==========================================================================
    PROTECTED ADMIN ROUTES (AdminMiddleware)
   ========================================================================== */
Route::middleware([AdminMiddleware::class])->group(function () {

    // ១. ទំព័រផ្ទាំងគ្រប់គ្រងទូទៅគណនាស្ថិតិ Dynamic (Dashboard)
    Route::get('/admin/dashboard', [SeedController::class, 'adminDashboard']);

    // ២. គ្រប់គ្រងទិន្នន័យគ្រាប់ពូជ (Seeds Management)
    Route::get('/admin/seeds', [SeedController::class, 'index']);
    Route::get('/admin/seeds/create', [SeedController::class, 'create']);
    Route::post('/admin/seeds/store', [SeedController::class, 'store']);
    Route::get('/admin/seeds/edit/{id}', [SeedController::class, 'edit']);
    Route::put('/admin/seeds/update/{id}', [SeedController::class, 'update']);
    Route::delete('/admin/seeds/delete/{id}', [SeedController::class, 'destroy']);

    // ៣. គ្រប់គ្រងខ្សែសង្វាក់ប្រភពដើមតាមដាន (Traceability Management)
    Route::get('/admin/traceability', [SeedController::class, 'adminTraceability']);
    Route::get('/admin/seeds/traceability/{seed_id}', [SeedController::class, 'createTraceability']);
    Route::post('/admin/seeds/traceability/store/{seed_id}', [SeedController::class, 'storeTraceability']);
    Route::put('/admin/traceability/update/{id}', [SeedController::class, 'updateTraceability']);
    Route::delete('/admin/traceability/delete/{id}', [SeedController::class, 'destroyTraceability']);
    Route::get('/admin/traceability/show/{id}', [SeedController::class, 'showTraceability']);

    // ៤. គ្រប់គ្រងបញ្ជីការកម្ម៉ង់ទិញសរុបរបស់អតិថិជន (Admin Orders Management)
    Route::get('/admin/orders', [SeedController::class, 'adminOrders']);
    Route::post('/admin/orders/status/{id}', [SeedController::class, 'updateOrderStatus']);
    Route::delete('/admin/orders/delete/{id}', [SeedController::class, 'destroyOrder']);

    // ៥. គ្រប់គ្រងប្រភេទបន្លែ (Admin Categories Management)
    Route::get('/admin/categories', [SeedController::class, 'adminCategories']);
    Route::post('/admin/categories/store', [SeedController::class, 'storeCategory']);
    Route::put('/admin/categories/update/{id}', [SeedController::class, 'updateCategory']);
    Route::delete('/admin/categories/delete/{id}', [SeedController::class, 'destroyCategory']);

    // ៦. គ្រប់គ្រងបញ្ជីអតិថិជន (Admin Customers Management)
    Route::get('/admin/customers', [SeedController::class, 'adminCustomers']);

    // ៧. បន្ថែមស្តុកបន្ទាន់ (Quick Restock)
    Route::post('/admin/seeds/quick-restock/{id}', [SeedController::class, 'quickRestock']);

    // ៨. ទាញយកទិន្នន័យជារបាយការណ៍ CSV (CSV Exports)
    Route::get('/admin/orders/export', [SeedController::class, 'exportOrdersCsv']);
    Route::get('/admin/seeds/export', [SeedController::class, 'exportSeedsCsv']);

    // ៩. បោះពុម្ពស្លាក Sticker គ្រាប់ពូជជាសន្លឹក (Print Packaging Labels)
    Route::get('/admin/seeds/print-labels/{id}', [SeedController::class, 'printLabels']);
});
