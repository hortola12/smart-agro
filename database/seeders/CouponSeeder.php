<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coupon::updateOrCreate(
            ['code' => 'AGRO2026'],
            [
                'description' => 'បញ្ចុះតម្លៃ ១០% លើគ្រប់ការបញ្ជាទិញគ្រាប់ពូជកសិកម្ម',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_order_amount' => 0,
                'max_discount_amount' => 10.00,
                'usage_limit' => 500,
                'expires_at' => now()->addYear(),
                'is_active' => true,
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'FARMER1'],
            [
                'description' => 'បញ្ចុះតម្លៃ $1.00 លើការបញ្ជាទិញចាប់ពី $5.00 ឡើងទៅ',
                'discount_type' => 'fixed',
                'discount_value' => 1.00,
                'min_order_amount' => 5.00,
                'usage_limit' => 200,
                'expires_at' => now()->addYear(),
                'is_active' => true,
            ]
        );
    }
}
