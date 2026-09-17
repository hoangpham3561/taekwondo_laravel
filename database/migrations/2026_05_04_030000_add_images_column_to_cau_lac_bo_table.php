<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cau_lac_bo', function (Blueprint $table) {
            if (! Schema::hasColumn('cau_lac_bo', 'images')) {
                if (Schema::hasColumn('cau_lac_bo', 'logo_url')) {
                    $table->text('images')->nullable()->after('logo_url')->comment('Danh sách ảnh (JSON array)');
                } else {
                    $table->text('images')->nullable()->comment('Danh sách ảnh (JSON array)');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('cau_lac_bo', function (Blueprint $table) {
            if (Schema::hasColumn('cau_lac_bo', 'images')) {
                $table->dropColumn('images');
            }
        });
    }
};
