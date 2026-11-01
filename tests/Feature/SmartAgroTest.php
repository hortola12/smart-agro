<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Seed;
use App\Models\SeedReview;
use App\Models\SeedTraceability;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmartAgroTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully_with_seeds(): void
    {
        $category = Category::create([
            'name_kh' => 'បន្លែយកស្លឹក',
            'name_en' => 'Leafy Vegetables',
        ]);

        $seed = Seed::create([
            'category_id' => $category->id,
            'name_kh' => 'ពូជសាឡាត់',
            'name_en' => 'Lettuce Seed',
            'price' => 2.50,
            'stock' => 50,
            'batch_number' => 'BATCH-TEST-001',
        ]);

        SeedTraceability::create([
            'seed_id' => $seed->id,
            'batch_number' => 'BATCH-TEST-001',
            'origin_kh' => 'បាត់ដំបង',
            'origin_en' => 'Battambang',
            'germination_rate' => 95,
            'harvest_date' => '2026-05-01',
            'expiry_date' => '2027-05-01',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Grow2Growth');
        $response->assertSee('ពូជសាឡាត់');
    }

    public function test_trace_route_finds_batch_by_batch_number_and_search_query(): void
    {
        $category = Category::create([
            'name_kh' => 'បន្លែយកស្លឹក',
            'name_en' => 'Leafy Vegetables',
        ]);

        $seed = Seed::create([
            'category_id' => $category->id,
            'name_kh' => 'ពូជសាឡាត់',
            'name_en' => 'Lettuce Seed',
            'price' => 2.50,
            'stock' => 50,
            'batch_number' => 'BATCH-QR-999',
        ]);

        SeedTraceability::create([
            'seed_id' => $seed->id,
            'batch_number' => 'BATCH-QR-999',
            'origin_kh' => 'បាត់ដំបង',
            'origin_en' => 'Battambang',
            'germination_rate' => 98,
            'harvest_date' => '2026-05-01',
            'expiry_date' => '2027-05-01',
        ]);

        // Test QR code endpoint /trace?batch_number=...
        $response1 = $this->get('/trace?batch_number=BATCH-QR-999');
        $response1->assertStatus(200);
        $response1->assertSee('BATCH-QR-999');

        // Test form search endpoint /trace-batch?search=...
        $response2 = $this->get('/trace-batch?search=BATCH-QR-999');
        $response2->assertStatus(200);
        $response2->assertSee('BATCH-QR-999');
    }

    public function test_checkout_creates_order_with_authenticated_user_id(): void
    {
        $user = User::create([
            'name' => 'សុខ ពិសិដ្ឋ',
            'email' => 'piseth@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $category = Category::create([
            'name_kh' => 'បន្លែយកស្លឹក',
            'name_en' => 'Leafy Vegetables',
        ]);

        $seed = Seed::create([
            'category_id' => $category->id,
            'name_kh' => 'ពូជសាឡាត់',
            'name_en' => 'Lettuce Seed',
            'price' => 3.00,
            'stock' => 10,
            'batch_number' => 'BATCH-ORD-01',
        ]);

        // Add to cart via session
        $this->actingAs($user)->post('/add-to-cart/'.$seed->id);

        $response = $this->actingAs($user)->post('/checkout', [
            'customer_name' => 'សុខ ពិសិដ្ឋ (Sok Piseth)',
            'customer_phone' => '012345678',
            'delivery_address' => 'ភូមិតាក្រាម ឃុំតាក្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង',
        ]);

        $response->assertRedirect('/my-orders');

        // Verify order has user_id
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'customer_phone' => '012345678',
        ]);

        // Verify my-orders displays this order
        $historyResponse = $this->actingAs($user)->get('/my-orders');
        $historyResponse->assertStatus(200);
        $historyResponse->assertSee('012345678');
    }

    public function test_login_rate_limiting_blocks_after_too_many_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'wrong@example.com',
                'password' => 'wrongpass',
            ]);
        }

        // The 6th attempt should trigger 429 Too Many Requests
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(429);
    }

    public function test_admin_seed_list_is_paginated(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'name_kh' => 'បន្លែ',
            'name_en' => 'Vegetables',
        ]);

        // Create 12 seeds to exceed 10 per page
        for ($i = 1; $i <= 12; $i++) {
            Seed::create([
                'category_id' => $category->id,
                'name_kh' => "ពូជទី {$i}",
                'name_en' => "Seed {$i}",
                'price' => 1.50,
                'stock' => 20,
                'batch_number' => "BATCH-{$i}",
            ]);
        }

        $response = $this->actingAs($admin)->get('/admin/seeds');
        $response->assertStatus(200);
        $response->assertViewHas('seeds');

        $seeds = $response->viewData('seeds');
        $this->assertEquals(10, $seeds->count()); // Only 10 on page 1
        $this->assertEquals(12, $seeds->total()); // 12 in total
    }

    public function test_stock_is_deducted_at_checkout_and_restored_on_cancellation(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_stock@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer_stock@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'customer',
        ]);

        $category = Category::create([
            'name_kh' => 'បន្លែ',
            'name_en' => 'Vegetables',
        ]);

        $seed = Seed::create([
            'category_id' => $category->id,
            'name_kh' => 'ពូជសាឡាត់ពិសេស',
            'name_en' => 'Special Lettuce',
            'price' => 2.00,
            'stock' => 20,
            'batch_number' => 'BATCH-STOCK-01',
        ]);

        // 1. Adding to cart should NOT reduce stock in database
        $this->actingAs($customer)->post('/add-to-cart/'.$seed->id);
        $this->assertEquals(20, $seed->fresh()->stock);

        // 2. Checkout should deduct stock
        $this->actingAs($customer)->post('/checkout', [
            'customer_name' => 'Customer',
            'customer_phone' => '098765432',
            'delivery_address' => 'Phnom Penh',
        ]);
        $this->assertEquals(19, $seed->fresh()->stock);

        $order = Order::latest()->first();

        // 3. Admin cancelling order restores stock
        $this->actingAs($admin)->post('/admin/orders/status/'.$order->id, [
            'status' => 'cancelled',
        ]);
        $this->assertEquals(20, $seed->fresh()->stock);
    }

    public function test_admin_can_manage_categories(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_cat@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        // 1. Visit category index
        $response = $this->actingAs($admin)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('គ្រប់គ្រងប្រភេទបន្លែ');

        // 2. Store new category
        $storeResponse = $this->actingAs($admin)->post('/admin/categories/store', [
            'name_kh' => 'ពពួកមើម',
            'name_en' => 'Root Vegetables',
            'description' => 'បន្លែដែលប្រមូលផលក្រោមដី',
        ]);
        $storeResponse->assertRedirect();
        $this->assertDatabaseHas('categories', ['name_en' => 'Root Vegetables']);

        $category = Category::where('name_en', 'Root Vegetables')->first();

        // 3. Update category
        $updateResponse = $this->actingAs($admin)->put('/admin/categories/update/'.$category->id, [
            'name_kh' => 'ពពួកមើមពិសេស',
            'name_en' => 'Special Root Vegetables',
            'description' => 'បន្លែប្រភេទមើមសរីរាង្គ',
        ]);
        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('categories', ['name_en' => 'Special Root Vegetables']);

        // 4. If category has seeds, delete is prevented
        $seed = Seed::create([
            'category_id' => $category->id,
            'name_kh' => 'ការ៉ុត',
            'name_en' => 'Carrot',
            'price' => 1.20,
            'stock' => 15,
            'batch_number' => 'BATCH-CARROT-01',
        ]);

        $deleteResponse1 = $this->actingAs($admin)->delete('/admin/categories/delete/'.$category->id);
        $deleteResponse1->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);

        // 5. If category has no seeds, delete succeeds
        $seed->delete();
        $deleteResponse2 = $this->actingAs($admin)->delete('/admin/categories/delete/'.$category->id);
        $deleteResponse2->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_can_view_customers_list(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_cust@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        $customer = User::create([
            'name' => 'កសិករ រ័ត្ន',
            'email' => 'roth_farmer@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'customer',
        ]);

        Order::create([
            'user_id' => $customer->id,
            'total_price' => 45.00,
            'status' => 'completed',
            'customer_name' => $customer->name,
            'customer_phone' => '012345678',
            'delivery_address' => 'Battambang',
        ]);

        $response = $this->actingAs($admin)->get('/admin/customers');
        $response->assertStatus(200);
        $response->assertSee('គ្រប់គ្រងបញ្ជីអតិថិជន');
        $response->assertSee('កសិករ រ័ត្ន');
        $response->assertSee('roth_farmer@smartagro.kh');
        $response->assertSee('$45.00');
    }

    public function test_search_supports_category_filter(): void
    {
        $cat1 = Category::create(['name_kh' => 'បន្លែស្លឹក', 'name_en' => 'Leafy']);
        $cat2 = Category::create(['name_kh' => 'បន្លែផ្លែ', 'name_en' => 'Fruit']);

        Seed::create([
            'category_id' => $cat1->id,
            'name_kh' => 'ស្ពៃក្តោប',
            'name_en' => 'Cabbage',
            'price' => 2.0,
            'stock' => 10,
            'batch_number' => 'BATCH-CAB',
        ]);

        Seed::create([
            'category_id' => $cat2->id,
            'name_kh' => 'ប៉េងប៉ោះ',
            'name_en' => 'Tomato',
            'price' => 3.0,
            'stock' => 10,
            'batch_number' => 'BATCH-TOM',
        ]);

        $response = $this->get('/search?category_id='.$cat1->id);
        $response->assertStatus(200);
        $response->assertSee('ស្ពៃក្តោប');
        $response->assertDontSee('ប៉េងប៉ោះ');
    }

    public function test_add_to_cart_supports_custom_quantity(): void
    {
        $cat = Category::create(['name_kh' => 'បន្លែ', 'name_en' => 'Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'សាឡាត់',
            'name_en' => 'Lettuce',
            'price' => 1.5,
            'stock' => 20,
            'batch_number' => 'BATCH-QTY',
        ]);

        $this->post('/add-to-cart/'.$seed->id, ['quantity' => 5]);

        $cart = session('cart');
        $this->assertNotNull($cart);
        $this->assertEquals(5, $cart[$seed->id]['quantity']);
    }

    public function test_admin_can_quick_restock_seed(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_restock@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        $cat = Category::create(['name_kh' => 'បន្លែ', 'name_en' => 'Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'ត្រកួន',
            'name_en' => 'Morning Glory',
            'price' => 1.0,
            'stock' => 5,
            'batch_number' => 'BATCH-RESTOCK',
        ]);

        $response = $this->actingAs($admin)->post('/admin/seeds/quick-restock/'.$seed->id, [
            'quantity' => 25,
        ]);

        $response->assertRedirect();
        $this->assertEquals(30, $seed->fresh()->stock);
    }

    public function test_admin_can_export_orders_and_seeds_csv(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_csv@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        $cat = Category::create(['name_kh' => 'បន្លែ', 'name_en' => 'Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'ស្ពៃ',
            'name_en' => 'Bok Choy',
            'price' => 2.0,
            'stock' => 15,
            'batch_number' => 'BATCH-CSV',
        ]);

        Order::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '012345678',
            'delivery_address' => 'Battambang',
            'total_price' => 20.0,
            'status' => 'completed',
        ]);

        // Export Orders CSV
        $orderCsvResponse = $this->actingAs($admin)->get('/admin/orders/export');
        $orderCsvResponse->assertStatus(200);
        $this->assertTrue(str_contains($orderCsvResponse->headers->get('content-type'), 'text/csv'));

        // Export Seeds CSV
        $seedCsvResponse = $this->actingAs($admin)->get('/admin/seeds/export');
        $seedCsvResponse->assertStatus(200);
        $this->assertTrue(str_contains($seedCsvResponse->headers->get('content-type'), 'text/csv'));
    }

    public function test_admin_can_view_print_labels_sheet(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_labels@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        $cat = Category::create(['name_kh' => 'បន្លែយកស្លឹក', 'name_en' => 'Leafy']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'សាឡាត់បាត់ដំបង',
            'name_en' => 'Battambang Lettuce',
            'price' => 2.5,
            'stock' => 50,
            'batch_number' => 'BATCH-LABEL-01',
        ]);

        $response = $this->actingAs($admin)->get('/admin/seeds/print-labels/'.$seed->id.'?count=8');
        $response->assertStatus(200);
        $response->assertSee('សាឡាត់បាត់ដំបង');
        $response->assertSee('BATCH-LABEL-01');
        $response->assertSee('សន្លឹកបោះពុម្ពស្លាក QR');
    }

    public function test_seed_certificate_view_loads_successfully_with_lab_parameters(): void
    {
        $cat = Category::create(['name_kh' => 'បន្លែយកស្លឹក', 'name_en' => 'Leafy']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'សាឡាត់តាក្រាម',
            'name_en' => 'Battambang Lettuce',
            'price' => 2.5,
            'stock' => 100,
            'batch_number' => 'BATCH-CERT-99',
        ]);

        SeedTraceability::create([
            'seed_id' => $seed->id,
            'batch_number' => 'BATCH-CERT-99',
            'origin_kh' => 'កសិដ្ឋានតាក្រាម',
            'origin_en' => 'Takram Farm',
            'germination_rate' => 97.5,
            'harvest_date' => '2026-05-10',
            'expiry_date' => '2027-05-10',
        ]);

        $response = $this->get('/seed-certificate/'.$seed->id);
        $response->assertStatus(200);
        $response->assertSee('វិញ្ញាបនបត្រត្រួតពិនិត្យគុណភាពគ្រាប់ពូជ');
        $response->assertSee('សាឡាត់តាក្រាម');
        $response->assertSee('BATCH-CERT-99');
        $response->assertSee('97.5%');
    }

    public function test_public_order_tracking_finds_order_by_phone_and_order_id(): void
    {
        $cat = Category::create(['name_kh' => 'បន្លែ', 'name_en' => 'Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'ត្រសក់ទ្រើង',
            'name_en' => 'Cucumber',
            'price' => 3.0,
            'stock' => 20,
            'batch_number' => 'BATCH-CUC-01',
        ]);

        $order = Order::create([
            'customer_name' => 'សុខ គង់',
            'customer_phone' => '012999888',
            'delivery_address' => 'ក្រុងបាត់ដំបង',
            'total_price' => 15.0,
            'status' => 'shipping',
        ]);

        $order->items()->create([
            'seed_id' => $seed->id,
            'quantity' => 5,
            'price' => 3.0,
            'subtotal' => 15.0,
        ]);

        // 1. Initial page load without search
        $response = $this->get('/order-tracking');
        $response->assertStatus(200);
        $response->assertSee('តាមដានស្ថានភាពការកម្ម៉ង់');

        // 2. Search by phone number
        $searchByPhone = $this->get('/order-tracking?search=012999888');
        $searchByPhone->assertStatus(200);
        $searchByPhone->assertSee('សុខ គង់');
        $searchByPhone->assertSee('15.00');
        $searchByPhone->assertSee('កំពុងដឹកជញ្ជូន');

        // 3. Search by Order Reference #ORD-00001 or ID
        $searchById = $this->get('/order-tracking?search='.urlencode('#ORD-'.str_pad($order->id, 5, '0', STR_PAD_LEFT)));
        $searchById->assertStatus(200);
        $searchById->assertSee('សុខ គង់');

        // 4. Search by plain order ID
        $searchByNum = $this->get('/order-tracking?search='.$order->id);
        $searchByNum->assertStatus(200);
        $searchByNum->assertSee('សុខ គង់');
    }

    public function test_admin_orders_filter_by_date_and_displays_kpi_cards(): void
    {
        $admin = User::create([
            'name' => 'Admin Filter',
            'email' => 'admin_filter@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        Order::create([
            'customer_name' => 'Customer Today',
            'customer_phone' => '098111222',
            'delivery_address' => 'Phnom Penh',
            'total_price' => 25.0,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/admin/orders?date_filter=today');
        $response->assertStatus(200);
        $response->assertSee('ការកម្ម៉ង់សរុប');
        $response->assertSee('ចំណូលសរុប');
        $response->assertSee('Customer Today');
    }

    public function test_admin_can_view_daily_packing_manifest(): void
    {
        $admin = User::create([
            'name' => 'Admin Manifest',
            'email' => 'admin_manifest@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        $cat = Category::create(['name_kh' => 'បន្លែ', 'name_en' => 'Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'ស្ពៃចង្កឹះ',
            'name_en' => 'Choy Sum',
            'price' => 2.0,
            'stock' => 50,
            'batch_number' => 'BATCH-MAN-01',
        ]);

        $order = Order::create([
            'customer_name' => 'លោក ហេង',
            'customer_phone' => '077889900',
            'delivery_address' => 'Battambang',
            'total_price' => 10.0,
            'status' => 'pending',
        ]);

        $order->items()->create([
            'seed_id' => $seed->id,
            'quantity' => 5,
            'price' => 2.0,
            'subtotal' => 10.0,
        ]);

        $response = $this->actingAs($admin)->get('/admin/orders/packing-manifest');
        $response->assertStatus(200);
        $response->assertSee('បញ្ជីរៀបចំទំនិញ និងចាត់ចែងដឹកជញ្ជូនប្រចាំថ្ងៃ');
        $response->assertSee('ស្ពៃចង្កឹះ');
        $response->assertSee('5 កញ្ចប់');
        $response->assertSee('លោក ហេង');
    }

    public function test_admin_dashboard_displays_expiring_batches_alert(): void
    {
        $admin = User::create([
            'name' => 'Admin Expire',
            'email' => 'admin_expire@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        $cat = Category::create(['name_kh' => 'បន្លែ', 'name_en' => 'Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'ប៉េងប៉ោះជិតផុតកំណត់',
            'name_en' => 'Expiring Tomato',
            'price' => 3.0,
            'stock' => 20,
            'batch_number' => 'BATCH-EXP-01',
        ]);

        SeedTraceability::create([
            'seed_id' => $seed->id,
            'batch_number' => 'BATCH-EXP-01',
            'origin_kh' => 'បាត់ដំបង',
            'origin_en' => 'Battambang',
            'germination_rate' => 95,
            'harvest_date' => now()->subMonths(6)->toDateString(),
            'expiry_date' => now()->addDays(20)->toDateString(), // Expiring in 20 days!
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('ការជូនដំណឹង៖ គ្រាប់ពូជដែលជិតផុតកំណត់');
        $response->assertSee('ប៉េងប៉ោះជិតផុតកំណត់');
        $response->assertSee('BATCH-EXP-01');
    }

    public function test_customer_can_submit_review_for_seed(): void
    {
        $cat = Category::create(['name_kh' => 'បន្លែយកផ្លែ', 'name_en' => 'Fruit Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'ពូជត្រសក់ស្រូវ',
            'name_en' => 'Melon Seed',
            'price' => 3.50,
            'stock' => 30,
            'batch_number' => 'BATCH-REV-01',
        ]);

        $response = $this->post("/seed-detail/{$seed->id}/review", [
            'customer_name' => 'សុខ វិសាល',
            'customer_phone' => '012999888',
            'rating' => 5,
            'comment' => 'គ្រាប់ពូជនេះដុះលឿនណាស់ ផ្លែធំផ្អែមឆ្ងាញ់!',
        ]);

        $response->assertSessionHas('review_success');
        $this->assertDatabaseHas('seed_reviews', [
            'seed_id' => $seed->id,
            'customer_name' => 'សុខ វិសាល',
            'rating' => 5,
            'comment' => 'គ្រាប់ពូជនេះដុះលឿនណាស់ ផ្លែធំផ្អែមឆ្ងាញ់!',
        ]);
    }

    public function test_seed_detail_displays_reviews_and_average_rating(): void
    {
        $cat = Category::create(['name_kh' => 'បន្លែយកផ្លែ', 'name_en' => 'Fruit Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'ពូជឪឡឹក',
            'name_en' => 'Watermelon Seed',
            'price' => 4.00,
            'stock' => 20,
            'batch_number' => 'BATCH-WATER-01',
        ]);

        SeedReview::create([
            'seed_id' => $seed->id,
            'customer_name' => 'កសិករ មករា',
            'rating' => 5,
            'comment' => 'ផ្លែធំ ទម្ងន់ធ្ងន់ល្អណាស់',
            'is_approved' => true,
        ]);

        SeedReview::create([
            'seed_id' => $seed->id,
            'customer_name' => 'កសិករ ចាន់ណា',
            'rating' => 4,
            'comment' => 'អត្រាដុះ ៩៨% ពេញចិត្តខ្លាំង',
            'is_approved' => true,
        ]);

        $response = $this->get("/seed-detail/{$seed->id}");
        $response->assertStatus(200);
        $response->assertSee('4.5'); // Average (5 + 4) / 2 = 4.5
        $response->assertSee('កសិករ មករា');
        $response->assertSee('កសិករ ចាន់ណា');
        $response->assertSee('អត្រាដុះ ៩៨% ពេញចិត្តខ្លាំង');
    }

    public function test_admin_can_view_and_delete_reviews(): void
    {
        $admin = User::create([
            'name' => 'Admin Reviews',
            'email' => 'admin_reviews@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        $cat = Category::create(['name_kh' => 'បន្លែយកស្លឹក', 'name_en' => 'Leafy Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'ស្ពៃក្តោប',
            'name_en' => 'Cabbage',
            'price' => 2.00,
            'stock' => 15,
            'batch_number' => 'BATCH-CAB-01',
        ]);

        $review = SeedReview::create([
            'seed_id' => $seed->id,
            'customer_name' => 'Spam User',
            'rating' => 1,
            'comment' => 'Inappropriate comment content',
            'is_approved' => true,
        ]);

        // View reviews in admin panel
        $response = $this->actingAs($admin)->get('/admin/reviews');
        $response->assertStatus(200);
        $response->assertSee('Inappropriate comment content');
        $response->assertSee('Spam User');

        // Delete review
        $deleteResponse = $this->actingAs($admin)->delete("/admin/reviews/delete/{$review->id}");
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('seed_reviews', [
            'id' => $review->id,
        ]);
    }

    public function test_user_can_toggle_wishlist_via_post_and_json(): void
    {
        $cat = Category::create(['name_kh' => 'បន្លែ', 'name_en' => 'Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'ត្រកួនចិន',
            'name_en' => 'Morning Glory',
            'price' => 1.50,
            'stock' => 10,
            'batch_number' => 'BATCH-WISH-01',
        ]);

        // Toggle Add via standard POST
        $response = $this->post("/wishlist/toggle/{$seed->id}");
        $response->assertSessionHas('wishlist_status');
        $this->assertTrue(isset(session('wishlist')[$seed->id]));

        // Toggle Remove via JSON AJAX
        $jsonResponse = $this->postJson("/wishlist/toggle/{$seed->id}");
        $jsonResponse->assertStatus(200);
        $jsonResponse->assertJson([
            'success' => true,
            'added' => false,
            'count' => 0,
        ]);
        $this->assertFalse(isset(session('wishlist')[$seed->id]));
    }

    public function test_wishlist_page_displays_saved_seeds(): void
    {
        $cat = Category::create(['name_kh' => 'បន្លែ', 'name_en' => 'Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'ម្ទេសហាវៃ',
            'name_en' => 'Bell Pepper',
            'price' => 3.20,
            'stock' => 25,
            'batch_number' => 'BATCH-PEPPER-01',
        ]);

        // Add to wishlist session
        $this->withSession([
            'wishlist' => [
                $seed->id => [
                    'id' => $seed->id,
                    'name' => $seed->name_kh,
                    'price' => $seed->price,
                    'image' => null,
                ],
            ],
        ]);

        $response = $this->get('/wishlist');
        $response->assertStatus(200);
        $response->assertSee('គ្រាប់ពូជពេញចិត្តរបស់អ្នក');
        $response->assertSee('ម្ទេសហាវៃ');
        $response->assertSee('3.20');
    }

    public function test_user_can_move_wishlist_seed_to_cart_and_clear_wishlist(): void
    {
        $cat = Category::create(['name_kh' => 'បន្លែ', 'name_en' => 'Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'ការ៉ុត',
            'name_en' => 'Carrot Seed',
            'price' => 2.80,
            'stock' => 12,
            'batch_number' => 'BATCH-CARROT-01',
        ]);

        $this->withSession([
            'wishlist' => [
                $seed->id => [
                    'id' => $seed->id,
                    'name' => $seed->name_kh,
                    'price' => $seed->price,
                    'image' => null,
                ],
            ],
        ]);

        // Move to Cart
        $moveResponse = $this->post("/wishlist/move-to-cart/{$seed->id}");
        $moveResponse->assertSessionHas('success');
        $this->assertTrue(isset(session('cart')[$seed->id]));
        $this->assertFalse(isset(session('wishlist')[$seed->id]));

        // Clear Wishlist
        $this->withSession([
            'wishlist' => [
                $seed->id => ['name' => 'Test'],
            ],
        ]);
        $clearResponse = $this->post('/wishlist/clear');
        $clearResponse->assertSessionHas('success');
        $this->assertEmpty(session('wishlist', []));
    }

    public function test_user_can_apply_and_remove_coupon_in_cart(): void
    {
        $cat = Category::create(['name_kh' => 'បន្លែ', 'name_en' => 'Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'សណ្តែកកួរ',
            'name_en' => 'Long Bean',
            'price' => 5.00,
            'stock' => 20,
            'batch_number' => 'BATCH-BEAN-01',
        ]);

        $coupon = Coupon::create([
            'code' => 'TEST10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_order_amount' => 5.00,
            'is_active' => true,
        ]);

        // Cart with 2 packs = $10.00
        $this->withSession([
            'cart' => [
                $seed->id => [
                    'name' => $seed->name_kh,
                    'price' => 5.00,
                    'quantity' => 2,
                    'stock' => 20,
                ],
            ],
        ]);

        // Apply valid coupon
        $response = $this->post('/cart/apply-coupon', [
            'code' => 'test10', // lowercase input should work (auto uppercased)
        ]);

        $response->assertSessionHas('coupon_success');
        $this->assertEquals('TEST10', session('applied_coupon.code'));
        $this->assertEquals(1.00, session('applied_coupon.discount_amount')); // 10% of $10 = $1.00

        // Remove coupon
        $removeResponse = $this->post('/cart/remove-coupon');
        $removeResponse->assertSessionHas('coupon_success');
        $this->assertNull(session('applied_coupon'));
    }

    public function test_checkout_applies_discount_and_tracks_coupon_usage(): void
    {
        $user = User::create([
            'name' => 'កសិករ តារា',
            'email' => 'dara@example.com',
            'password' => bcrypt('password'),
            'role' => 'farmer',
        ]);

        $cat = Category::create(['name_kh' => 'បន្លែ', 'name_en' => 'Veg']);
        $seed = Seed::create([
            'category_id' => $cat->id,
            'name_kh' => 'ត្រប់វែង',
            'name_en' => 'Eggplant',
            'price' => 4.00,
            'stock' => 10,
            'batch_number' => 'BATCH-EGG-01',
        ]);

        $coupon = Coupon::create([
            'code' => 'SAVE2',
            'discount_type' => 'fixed',
            'discount_value' => 2.00,
            'min_order_amount' => 4.00,
            'usage_limit' => 10,
            'used_count' => 0,
            'is_active' => true,
        ]);

        // 2 items * $4.00 = $8.00 subtotal. Minus $2.00 discount = $6.00 final total
        $this->actingAs($user)->withSession([
            'cart' => [
                $seed->id => [
                    'name' => $seed->name_kh,
                    'price' => 4.00,
                    'quantity' => 2,
                    'stock' => 10,
                ],
            ],
            'applied_coupon' => [
                'id' => $coupon->id,
                'code' => 'SAVE2',
                'discount_type' => 'fixed',
                'discount_value' => 2.00,
                'discount_amount' => 2.00,
            ],
        ]);

        $checkoutResponse = $this->post('/checkout', [
            'customer_name' => 'កសិករ តារា',
            'customer_phone' => '012888999',
            'delivery_address' => 'ក្រុងបាត់ដំបង ខេត្តបាត់ដំបង',
        ]);

        $checkoutResponse->assertRedirect('/my-orders');

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'កសិករ តារា',
            'total_price' => 6.00,
            'coupon_code' => 'SAVE2',
            'discount_amount' => 2.00,
        ]);

        $this->assertEquals(1, $coupon->fresh()->used_count);
    }

    public function test_admin_can_manage_coupons(): void
    {
        $admin = User::create([
            'name' => 'Admin Coupon',
            'email' => 'admin_coupon@smartagro.kh',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        // 1. Create Coupon
        $response = $this->actingAs($admin)->post('/admin/coupons', [
            'code' => 'ADMIN50',
            'description' => 'Half price promo',
            'discount_type' => 'percentage',
            'discount_value' => 50,
            'min_order_amount' => 10,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('coupons', [
            'code' => 'ADMIN50',
            'discount_value' => 50,
        ]);

        $coupon = Coupon::where('code', 'ADMIN50')->first();

        // 2. View in Admin List
        $viewResponse = $this->actingAs($admin)->get('/admin/coupons');
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('ADMIN50');

        // 3. Toggle Status
        $toggleResponse = $this->actingAs($admin)->post("/admin/coupons/{$coupon->id}/toggle");
        $toggleResponse->assertSessionHas('success');
        $this->assertFalse($coupon->fresh()->is_active);

        // 4. Delete Coupon
        $deleteResponse = $this->actingAs($admin)->delete("/admin/coupons/{$coupon->id}");
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    }
}
