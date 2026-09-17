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
        Schema::table('tin_tuc', function (Blueprint $table) {
            $table->foreignId('cate_id')->nullable()->after('author_id')->constrained('category')->onDelete('set null')->comment('Category ID for news');
            $table->string('status', 50)->nullable()->after('is_published')->comment('News status');
            $table->string('seo_title', 255)->nullable()->after('status');
            $table->text('seo_description')->nullable()->after('seo_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tin_tuc', function (Blueprint $table) {
            $table->dropForeign(['cate_id']);
            $table->dropColumn(['cate_id', 'status', 'seo_title', 'seo_description']);
        });
    }
};

