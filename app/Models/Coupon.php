<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'ebook_id',
        'discount_percent',
        'discount_amount',
        'valid_from',
        'valid_until',
        'usage_limit',
        'used_count',
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
            return round($price * ($this->discount_percent / 100), 2);
        }

        return (float) ($this->discount_amount ?? 0);
    }

    /** Prix final après application du coupon (jamais négatif). */
    public function finalPrice(float $price): float
    {
        return max(0, round($price - $this->getDiscountAmount($price), 2));
    }

    /** Le coupon est-il applicable à cet ebook ? (global ou ciblé) */
    public function isValidForEbook(Ebook $ebook): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        // ebook_id null = coupon global ; sinon doit correspondre
        return $this->ebook_id === null || (int) $this->ebook_id === (int) $ebook->id;
    }

    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class);
    }
}
