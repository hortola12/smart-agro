<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Seed;
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
}
