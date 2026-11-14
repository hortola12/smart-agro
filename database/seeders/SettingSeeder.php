<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            'cooperative_name_kh' => 'សហគមន៍កសិកម្មសុវត្ថិភាពតាក្រាម (Grow2Growth)',
            'cooperative_name_en' => 'Takream Safe Agriculture Cooperative (Grow2Growth)',
            'hotline_phone' => '0883322318',
            'telegram_link' => 'https://t.me/Hor_Tola12',
            'contact_email' => 'support@smartagro.kh',
            'farm_address_kh' => 'ភូមិតាក្រាម ឃុំតាក្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង',
            'farm_address_en' => 'Takream Village, Banan District, Battambang Province',
            'low_stock_threshold' => '5',
            'batch_expiry_warning_days' => '60',
            'bakong_account_name' => 'HOR TOLA',
            'bakong_account_number' => 'tola_hor@aclb',
            'default_shipping_fee' => '1.50',
        ];

        foreach ($defaults as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
