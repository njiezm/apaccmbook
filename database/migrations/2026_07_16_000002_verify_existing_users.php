<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Marque tous les comptes existants comme vérifiés au moment de l'activation
     * de la vérification d'email, pour ne pas bloquer les utilisateurs déjà inscrits.
     * Seuls les comptes créés APRÈS devront vérifier leur adresse.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
    }

    public function down(): void
    {
        // Irréversible : on ne « dé-vérifie » pas les comptes.
    }
};
