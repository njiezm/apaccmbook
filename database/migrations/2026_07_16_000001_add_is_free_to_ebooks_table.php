<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            if (!Schema::hasColumn('ebooks', 'is_free')) {
                $table->boolean('is_free')->default(false)->after('price');
            }
        });

        // Rendre helloasso_url nullable (livres gratuits / autres modes de paiement).
        // On évite ->change() car il génère « drop identity if exists », incompatible
        // avec la version de PostgreSQL d'o2switch. SQL natif selon le driver.
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE ebooks ALTER COLUMN helloasso_url DROP NOT NULL');
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE ebooks MODIFY helloasso_url VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            if (Schema::hasColumn('ebooks', 'is_free')) {
                $table->dropColumn('is_free');
            }
        });
    }
};
