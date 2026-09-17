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
        Schema::table('cap_dai', function (Blueprint $table) {
            $table->text('belt_description')->nullable()->after('description')->comment('Mô tả đai (ví dụ: Đai trắng Một vạch xanh dương)');
            $table->integer('minimum_time_months')->nullable()->after('belt_description')->comment('Thời gian tối thiểu tính bằng tháng');
            $table->integer('minimum_age')->nullable()->after('minimum_time_months')->comment('Độ tuổi tối thiểu');
            $table->text('age_requirement_note')->nullable()->after('minimum_age')->comment('Ghi chú về độ tuổi (ví dụ: Quý I của năm dự thi)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cap_dai', function (Blueprint $table) {
            $table->dropColumn(['belt_description', 'minimum_time_months', 'minimum_age', 'age_requirement_note']);
        });
    }
};
