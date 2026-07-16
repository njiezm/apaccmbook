<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            // Ciblage : coupon global (null) ou limité à un ebook précis
            if (!Schema::hasColumn('coupons', 'ebook_id')) {
                $table->foreignId('ebook_id')->nullable()->after('code')->constrained()->nullOnDelete();
            }
        });

        Schema::table('purchases', function (Blueprint $table) {
            if (!Schema::hasColumn('purchases', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('purchases', 'final_price')) {
                $table->decimal('final_price', 10, 2)->nullable()->after('coupon_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasColumn('coupons', 'ebook_id')) {
                $table->dropConstrainedForeignId('ebook_id');
            }
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['coupon_code', 'final_price']);
        });
    }
};
