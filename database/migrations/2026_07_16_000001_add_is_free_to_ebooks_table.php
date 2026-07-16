<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->boolean('is_free')->default(false)->after('price');
        });

        // helloasso_url doit pouvoir être nul (livres gratuits, ou autres modes de paiement)
        Schema::table('ebooks', function (Blueprint $table) {
            $table->string('helloasso_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->dropColumn('is_free');
        });
    }
};
