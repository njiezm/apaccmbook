<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\EbookSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrateur',
            'email' => 'admin@apacc-m.fr',
            'is_admin' => true,
            'password' => bcrypt('secret123'),
        ]);

        User::factory()->create([
            'name' => 'Lecteur martiniquais',
            'email' => 'lecteur@apacc-m.fr',
        ]);

        $this->call(EbookSeeder::class);
    }
}
