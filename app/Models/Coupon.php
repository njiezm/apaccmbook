<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percent',
        'discount_amount',
        'valid_from',
        'valid_until',
        'usage_limit',
        'is_active',
    ];

    protected $casts = [
        'discount_percent' => 'integer',
        'discount_amount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($now < $this->valid_from) {
            return false;
        }

        if ($this->valid_until && $now > $this->valid_until) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function getDiscountAmount(float $price): float
    {
        if ($this->discount_percent) {
            return $price * ($this->discount_percent / 100);
        }

        return (float) $this->discount_amount ?? 0;
    }
}
