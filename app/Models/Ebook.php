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
        'sumup_url',
        'category_id',
        'author_id',
        'page_count',
        'published_date',
        'status',
        'is_transandans',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_free' => 'boolean',
        'is_transandans' => 'boolean',
        'published_date' => 'date',
    ];

    /**
     * Ouvrages visibles du public : publiés ET dont la date de publication est
     * nulle ou déjà passée (permet la programmation d'une parution future).
     */
    public function scopeVisible($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_date')
                  ->orWhereDate('published_date', '<=', now()->toDateString());
            });
    }

    public function isVisible(): bool
    {
        return $this->status === 'published'
            && (is_null($this->published_date)
                || $this->published_date->toDateString() <= now()->toDateString());
    }

    /** État lisible pour l'admin : brouillon / programmé / publié / archivé. */
    public function publicationState(): string
    {
        return match (true) {
            $this->status === 'draft'    => 'brouillon',
            $this->status === 'archived' => 'archivé',
            !is_null($this->published_date) && $this->published_date->toDateString() > now()->toDateString() => 'programmé',
            default => 'publié',
        };
    }

    /**
     * Sommaire normalisé : collection d'entrées ['title', 'subtitle', 'page'].
     * Gère le nouveau format JSON ET l'ancien format texte (une ligne par entrée,
     * n° de page en fin de ligne), pour rester rétro-compatible sans migration de données.
     */
    public function sommaireEntries(): \Illuminate\Support\Collection
    {
        $raw = trim((string) $this->sommaire);

        if ($raw === '') {
            return collect();
        }

        // Nouveau format : JSON [{title, subtitle, page}, …]
        if (str_starts_with($raw, '[')) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                return collect($decoded)
                    ->map(fn ($i) => [
                        'title'    => trim((string) ($i['title'] ?? '')),
                        'subtitle' => trim((string) ($i['subtitle'] ?? '')),
                        'page'     => isset($i['page']) && $i['page'] !== '' ? (int) $i['page'] : null,
                    ])
                    ->filter(fn ($i) => $i['title'] !== '')
                    ->values();
            }
        }

        // Ancien format : une entrée par ligne, n° de page éventuel en fin de ligne
        return collect(preg_split('/\r\n|\r|\n/', $raw))
            ->map(fn ($l) => trim($l))
            ->filter()
            ->map(function ($line) {
                preg_match('/^(.*?)[\s.\-–—]*(\d+)\s*$/u', $line, $m);
                return [
                    'title'    => trim($m[1] ?? $line),
                    'subtitle' => '',
                    'page'     => isset($m[2]) ? (int) $m[2] : null,
                ];
            })
            ->values();
    }

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
