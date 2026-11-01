<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeedReview extends Model
{
    protected $fillable = [
        'seed_id',
        'user_id',
        'customer_name',
        'customer_phone',
        'rating',
        'comment',
        'is_approved',
    ];

    /**
     * 🔗 ទំនាក់ទំនងទៅកាន់គ្រាប់ពូជ (BelongsTo Seed)
     */
    public function seed()
    {
        return $this->belongsTo(Seed::class);
    }

    /**
     * 🔗 ទំនាក់ទំនងទៅកាន់គណនីអ្នកប្រើប្រាស់ (BelongsTo User, Optional)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
