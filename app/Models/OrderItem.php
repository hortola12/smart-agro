<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'seed_id', 'quantity', 'price', 'subtotal'];

    // មុខទំនិញលម្អិតនិមួយៗ គឺឆ្លុះបញ្ចាំងទៅកាន់គ្រាប់ពូជចំតួមួយ (BelongsTo)
    public function seed()
    {
        return $this->belongsTo(Seed::class);
    }
}
