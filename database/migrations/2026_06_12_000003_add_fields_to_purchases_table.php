<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->timestamp('access_expires_at')->nullable()->after('created_at');
            $table->enum('status', ['pending', 'completed', 'refunded'])->default('completed')->after('access_expires_at');
            $table->string('payment_method')->nullable()->after('status');
            $table->string('transaction_id')->unique()->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['access_expires_at', 'status', 'payment_method', 'transaction_id']);
        });
    }
};
