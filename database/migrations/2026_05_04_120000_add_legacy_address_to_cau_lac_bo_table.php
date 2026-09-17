<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cau_lac_bo', function (Blueprint $table) {
            if (! Schema::hasColumn('cau_lac_bo', 'legacy_address')) {
                if (Schema::hasColumn('cau_lac_bo', 'address')) {
                    $table->text('legacy_address')->nullable()->after('address')->comment('Địa chỉ hành chính cũ trước sát nhập (tham chiếu)');
                } else {
                    $table->text('legacy_address')->nullable()->comment('Địa chỉ hành chính cũ trước sát nhập (tham chiếu)');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('cau_lac_bo', function (Blueprint $table) {
            if (Schema::hasColumn('cau_lac_bo', 'legacy_address')) {
                $table->dropColumn('legacy_address');
            }
        });
    }
};
