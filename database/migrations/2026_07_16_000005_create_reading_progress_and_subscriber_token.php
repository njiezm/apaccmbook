<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Reprise de lecture : dernière page lue par utilisateur et par ebook
        if (!Schema::hasTable('reading_progress')) {
            Schema::create('reading_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('ebook_id')->constrained()->cascadeOnDelete();
                $table->unsignedInteger('last_page')->default(1);
                $table->timestamps();
                $table->unique(['user_id', 'ebook_id']);
            });
        }

        // Double opt-in newsletter : jeton de confirmation
        Schema::table('subscribers', function (Blueprint $table) {
            if (!Schema::hasColumn('subscribers', 'confirmation_token')) {
                $table->string('confirmation_token')->nullable()->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_progress');
        Schema::table('subscribers', function (Blueprint $table) {
            if (Schema::hasColumn('subscribers', 'confirmation_token')) {
                $table->dropColumn('confirmation_token');
            }
        });
    }
};
