<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';

    protected $fillable = [
        'user_id',
        'ebook_id',
        'payment_status',
        'payment_method',
        'transaction_id',
        'coupon_code',
        'final_price',
    ];

    protected $attributes = [
        'payment_status' => self::STATUS_PENDING,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class);
    }
}
