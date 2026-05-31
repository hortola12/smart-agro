<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'name_kh' => 'បន្លែយកស្លឹក', 'name_en' => 'Leafy Vegetables'],
            ['id' => 2, 'name_kh' => 'បន្លែយកផ្លែ', 'name_en' => 'Fruit Vegetables'],
            ['id' => 3, 'name_kh' => 'បន្លែយកមើម', 'name_en' => 'Root/Tuber Vegetables'],
            ['id' => 4, 'name_kh' => 'បន្លែយកផ្កា', 'name_en' => 'Flower Vegetables'],
            ['id' => 5, 'name_kh' => 'ប្រភេទធញ្ញជាតិ', 'name_en' => 'Grains & Cereals'],
        ];

        foreach ($categories as $cat) {
            // 🟢 បើមាន ID ហ្នឹងហើយ វានឹង Update តែបើអត់ទាន់មាន វានឹង Insert ថ្មី (ការពារការគាំង)
            Category::updateOrCreate(['id' => $cat['id']], $cat);
        }
    }
}

//ជំហានទី ៣៖ រាល់ពេលចង់ឱ្យវាហោះចូល Database ទាំងអស់ក្នុងពេលតែ ១ វិនាទី សូមរត់បញ្ជានេះក្នុង Terminal៖
// php artisan db:seed --class=CategorySeeder
