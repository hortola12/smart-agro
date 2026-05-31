<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seed extends Model
{
    //  កែសម្រួល៖ រក្សាទុកតែជួរឈរពិតប្រាកដរបស់តារាង seeds តែប៉ុណ្ណោះ (កាត់បន្ថយការជលទិន្នន័យគ្នា)
    protected $fillable = [
        'category_id',
        'name_kh',
        'name_en',
        'description_kh',
        'description_en',
        'price',
        'stock',
        'image',
        'batch_number',
    ];

    /**
     * 🔗 ទំនាក់ទំនងទៅកាន់តារាង Categories (Many-to-One)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 🔗 ទំនាក់ទំនងទៅកាន់តារាង SeedTraceabilities បែបប្រវត្តិរួម (One-to-Many)
     */
    public function traceabilities()
    {
        return $this->hasMany(SeedTraceability::class, 'seed_id');
    }

    /**
     * 🔗 ទំនាក់ទំនងចម្បងចំតួទៅកាន់ព័ត៌មានកសិកម្មលម្អិត (One-to-One)
     * ប្រើសម្រាប់ទាញយកទិន្នន័យ pH ដី, របបទឹក, វិធីសាប មកបង្ហាញក្នុង Form Edit និងទំព័របញ្ជីរួម
     */
    public function traceability()
    {
        return $this->hasOne(SeedTraceability::class, 'seed_id');
    }
}
