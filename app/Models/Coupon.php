<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'float',
        'min_order_amount' => 'float',
        'max_discount_amount' => 'float',
        'is_active' => 'boolean',
        'expires_at' => 'date',
    ];

    /**
     * ផ្ទៀងផ្ទាត់ថាតើប័ណ្ណបញ្ចុះតម្លៃនេះនៅមានសុពលភាពសម្រាប់តម្លៃកន្ត្រកជាក់ស្តែងដែរឬទេ
     */
    public function isValidForAmount(float $amount): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast() && ! $this->expires_at->isToday()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($amount < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    /**
     * គណនាចំនួនទឹកប្រាក់ដែលត្រូវបញ្ចុះ (Discount Amount)
     */
    public function calculateDiscount(float $amount): float
    {
        if ($this->discount_type === 'percentage') {
            $discount = ($amount * $this->discount_value) / 100;
            if ($this->max_discount_amount !== null) {
                $discount = min($discount, $this->max_discount_amount);
            }
        } else {
            $discount = min($amount, $this->discount_value);
        }

        return round($discount, 2);
    }
}
