<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            // Marque l'ouvrage comme numéro de la revue Transandans (filtre catalogue).
            $table->boolean('is_transandans')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->dropColumn('is_transandans');
        });
    }
};
