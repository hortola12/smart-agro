<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeedTraceability extends Model
{
    // ឈ្មោះតារាងពិតប្រាកដនៅក្នុង Database
    protected $table = 'seed_traceabilities';

    //  កែសម្រួល៖ បន្ថែម 'origin_en' ចូលទៅក្នុង $fillable ដើម្បីអនុញ្ញាតឱ្យរក្សាទុកទិន្នន័យបានជោគជ័យ ១00%
    protected $fillable = [
        'seed_id', // Foreign Key ភ្ជាប់ទៅតារាង seeds
        'batch_number',
        'origin_kh',
        'origin_en', // 🔒 បើកសិទ្ធិឱ្យជួរឈរនេះ ដើម្បីការពារកំហុស Mass Assignment
        'germination_rate',
        'harvest_date',
        'expiry_date',
        'farm_name',
        'soil_ph',
        'watering_schedule',
        'cultivation_guide',
    ];

    /**
     * 🔗 ទំនាក់ទំនងបញ្ច្រាសត្រឡប់ទៅកាន់តារាង Seeds (Many-to-One / One-to-One Inverse)
     * ប្រាប់ប្រព័ន្ធថា ខ្សែសង្វាក់ Batch នេះ គឺជារបស់គ្រាប់ពូជ (Seed) ណាមួយ
     */
    public function seed()
    {
        return $this->belongsTo(Seed::class, 'seed_id');
    }
}
