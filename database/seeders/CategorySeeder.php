<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Théologie & Spiritualité', 'slug' => 'theologie-spiritualite', 'icon' => '✝️'],
            ['name' => 'Liturgie & Sacrements', 'slug' => 'liturgie-sacrements', 'icon' => '🙏'],
            ['name' => 'Pensée Catholique', 'slug' => 'pensee-catholique', 'icon' => '💭'],
            ['name' => 'Essais & Réflexions', 'slug' => 'essais-reflexions', 'icon' => '📝'],
            ['name' => 'Ressources Pédagogiques', 'slug' => 'ressources-pedagogiques', 'icon' => '📚'],
            ['name' => 'Spiritualité Pratique', 'slug' => 'spiritualite-pratique', 'icon' => '🕯️'],
            ['name' => 'Bible & Exégèse', 'slug' => 'bible-exegese', 'icon' => '📖'],
            ['name' => 'Histoire de l\'Église', 'slug' => 'histoire-eglise', 'icon' => '⛪'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
