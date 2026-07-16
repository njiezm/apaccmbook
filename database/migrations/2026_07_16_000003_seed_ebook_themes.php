<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Thèmes fixes proposés dans l'admin et le filtre du catalogue.
     * Idempotent : ne crée que ceux qui manquent.
     */
    private array $themes = [
        'Patrimoine religieux',
        'Spiritualité & foi',
        'Histoire',
        'Culture martiniquaise',
        'Musique & Bèlè',
        'Traditions & mémoire',
        'Art sacré',
        'Biographies & témoignages',
        'Théologie',
        'Famille & société',
    ];

    public function up(): void
    {
        foreach ($this->themes as $name) {
            Category::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
        }
    }

    public function down(): void
    {
        // On ne supprime pas les thèmes (des ebooks peuvent y être rattachés).
    }
};
