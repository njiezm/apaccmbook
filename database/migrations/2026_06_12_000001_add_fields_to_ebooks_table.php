<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->text('short_description')->nullable()->after('description');
            $table->unsignedBigInteger('category_id')->nullable()->after('price');
            $table->unsignedBigInteger('author_id')->nullable()->after('category_id');
            $table->integer('page_count')->nullable()->after('author_id');
            $table->date('published_date')->nullable()->after('page_count');
            $table->enum('status', ['draft', 'published', 'archived'])->default('published')->after('published_date');
        });

        // Backfill slugs for existing ebooks
        try {
            $ebooks = DB::table('ebooks')->whereNull('slug')->get();
            foreach ($ebooks as $ebook) {
                $slug = Str::slug($ebook->title);
                // Handle duplicate slugs
                $count = DB::table('ebooks')->where('slug', $slug)->count();
                if ($count > 0) {
                    $slug = $slug . '-' . $ebook->id;
                }
                DB::table('ebooks')
                    ->where('id', $ebook->id)
                    ->update(['slug' => $slug]);
            }
        } catch (\Exception $e) {
            // Silently handle if there are no rows
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->dropColumn(['slug', 'short_description', 'category_id', 'author_id', 'page_count', 'published_date', 'status']);
        });
    }
};
