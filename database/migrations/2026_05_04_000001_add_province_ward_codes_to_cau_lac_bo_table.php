<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cau_lac_bo', function (Blueprint $table) {
            if (! Schema::hasColumn('cau_lac_bo', 'province_code')) {
                $table->unsignedInteger('province_code')->nullable()->after('address');
            }
            if (! Schema::hasColumn('cau_lac_bo', 'ward_code')) {
                $table->unsignedInteger('ward_code')->nullable()->after('province_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cau_lac_bo', function (Blueprint $table) {
            if (Schema::hasColumn('cau_lac_bo', 'ward_code')) {
                $table->dropColumn('ward_code');
            }
            if (Schema::hasColumn('cau_lac_bo', 'province_code')) {
                $table->dropColumn('province_code');
            }
        });
    }
};
