<?php

namespace Database\Seeders;

use App\Models\Ebook;
use Illuminate\Database\Seeder;

class EbookSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'title' => 'Patrimoine en lumière',
                'description' => 'Une collection d’analyses visuelles et théologiques qui revisitent la Martinique à travers ses édifices sacrés.',
                'price' => 14.99,
                'file_path' => 'ebooks/heritage.pdf',
                'cover_image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80',
                'helloasso_url' => 'https://www.helloasso.com/associations/apacc-m/collectes/ebook-patrimoine',
            ],
            [
                'title' => 'Odes et visions',
                'description' => 'Textes de création contemporaine, méditations et carnets de voyages spirituels.',
                'price' => 11.5,
                'file_path' => 'ebooks/vision-culture.pdf',
                'cover_image' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=800&q=80',
                'helloasso_url' => 'https://www.helloasso.com/associations/apacc-m/collectes/ebook-visions',
            ],
            [
                'title' => 'Lumière d’appel',
                'description' => 'Entretiens, compositions et carnets d’atelier pour relire la spiritualité créole.',
                'price' => 9.9,
                'file_path' => 'ebooks/calling-light.pdf',
                'cover_image' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=800&q=80',
                'helloasso_url' => 'https://www.helloasso.com/associations/apacc-m/collectes/ebook-lumiere',
            ],
        ];

        foreach ($samples as $item) {
            Ebook::create($item);
        }
    }
}
