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
        'sommaire',
        'short_description',
        'price',
        'is_free',
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
        'is_free' => 'boolean',
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

    /** URL de la miniature (WebP) si disponible, sinon la couverture complète, sinon null. */
    public function thumbUrl(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }
        $thumb = \App\Support\CoverThumbnail::pathFor($this->cover_image);
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($thumb)) {
            return asset('storage/' . $thumb);
        }
        return asset('storage/' . $this->cover_image);
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
