<?php

namespace Tests\Feature;

use App\Models\Category;
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

        $response->assertRedirect('/');

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
}
