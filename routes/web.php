<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SeedController;
use Illuminate\Http\Request;

/* ==========================================================================
   🌾 FRONTEND / PUBLIC ROUTES (អ្នកណាក៏អាចចូលមើលបាន - មិនបាច់ Login)
   ========================================================================== */

// ១. ទំព័រដើមគេហទំព័រ Frontend (Homepage)
Route::get('/', function () {
    // 🟢 Eager Load 'traceability' ចូលទៅជាមួយ ដើម្បីឱ្យ Progress Bar ទំព័រដើមបង្ហាញអត្រាលូតលាស់ពិតប្រាកដចេញពី DB
    $seeds = \App\Models\Seed::with(['category', 'traceability'])->get();
    return view('home', compact('seeds'));
});

// ២. ទំព័រព័ត៌មានលម្អិតគ្រាប់ពូជ
Route::get('/seed-detail/{id}', [SeedController::class, 'seedDetail']);

// 🟢 កែសម្រួល៖ ប្តូរឱ្យទៅជា /change-language/ ឱ្យត្រូវគ្នា ១០០% ជាមួយប៊ូតុងប្តូរភាសា KH/EN លើរបារ Navbar
Route::get('/change-language/{locale}', [SeedController::class, 'changeLanguage']);

// 🔍 ផ្លូវសម្រាប់ស្វែងរកឈ្មោះគ្រាប់ពូជទូទៅ (Search Vegetable Seeds)
Route::get('/search', [SeedController::class, 'searchSeeds']);

// 🟢 ដោះស្រាយបញ្ហា 404៖ ប្តូរពី /trace មកជា /trace-batch ឱ្យត្រូវចំ Form ស្វែងរកលេខបាច់របស់កសិករ លែងលោត Error ទៀតហើយ!
Route::get('/trace-batch', [SeedController::class, 'traceBatch']);

// ៣. ទំព័រ Form សម្រាប់ឱ្យកសិករវាយបញ្ចូលលេខ Batch ស្វែងរក
Route::get('/traceability', function() {
    return view('traceability_search');
});


/* ==========================================================================
   🛒 PUBLIC CART SESSION ROUTES (ប្រព័ន្ធកន្ត្រកទំនិញទូទៅ)
   ========================================================================== */
Route::get('/cart', [SeedController::class, 'showCart']);
Route::post('/add-to-cart/{id}', [SeedController::class, 'addToCart']);
Route::patch('/update-cart', [SeedController::class, 'updateCart']);
Route::delete('/remove-from-cart', [SeedController::class, 'removeFromCart']);


/* ==========================================================================
   🔐 CUSTOM AUTHENTICATION ROUTES (ប្រព័ន្ធ Login / Register / Logout)
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
});

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

    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
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
// 🟢 សម្អាតរួចរាល់៖ ប្រមូលផ្តុំកូដដែលស្ទួនគ្នាបញ្ចូលគ្នាតែមួយ និងតម្រង់មុខងាររត់ទៅកាន់ SeedController ឱ្យស្របតាមកូដស្នូល
Route::middleware(['auth'])->group(function () {

    // ផ្លូវបើកទំព័រត្រួតពិនិត្យវិក្កយបត្រជំហានចុងក្រោយ (GET)
    Route::get('/checkout', function() {
        return view('checkout');
    })->name('checkout');

    // ផ្លូវ Submit Form ដឹកជញ្ជូន រក្សាទុកការទិញដូរ កាត់ស្តុក និងសម្អាតកន្ត្រក (POST)
    Route::post('/checkout', [SeedController::class, 'checkout']);

    // ផ្លូវចូលមើលប្រវត្តិបញ្ជាទិញផ្ទាល់ខ្លួនរបស់កសិករ/អតិថិជន
    Route::get('/my-orders', [SeedController::class, 'myOrders'])->name('my.orders');
});


/* ==========================================================================
   🛡️ PROTECTED ADMIN ROUTES (ផ្ទាំងគ្រប់គ្រងខាងក្រោយ ស្ថិតក្រោមច្បាប់ការពាររបស់ AdminMiddleware)
   ========================================================================== */
Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {

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
});
