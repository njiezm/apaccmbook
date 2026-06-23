<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'price',
        'file_path',
        'cover_image',
        'helloasso_url',
        'category_id',
        'author_id',
        'page_count',
        'published_date',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'published_date' => 'date',
    ];

    public static function booting()
    {
        parent::booting();

        static::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getAvgRatingAttribute()
    {
        return $this->reviews()->where('status', 'approved')->avg('rating') ?? 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->where('status', 'approved')->count();
    }
}
