<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //  ប្រកាសជួរឈរ (Columns) ទាំងអស់ដែលអនុញ្ញាតឱ្យរក្សាទុកទិន្នន័យ (Mass Assignment)
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'customer_name',
        'customer_phone',
        'delivery_address',
    ];

    /**
     * 👤 អតិថិជនដែលបានធ្វើការកម្ម៉ង់ទិញ (BelongsTo)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 📦 ការកម្មង់ទិញមួយ អាចមានមុខទំនិញលម្អិតច្រើន (One-to-Many)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
