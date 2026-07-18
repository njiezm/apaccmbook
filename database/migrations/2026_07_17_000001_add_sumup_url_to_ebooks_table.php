<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            // Lien de paiement SumUp propre à l'ouvrage (repli quand l'API SumUp
            // n'est pas configurée / activée) — même principe que helloasso_url.
            $table->string('sumup_url')->nullable()->after('helloasso_url');
        });
    }

    public function down(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->dropColumn('sumup_url');
        });
    }
};
