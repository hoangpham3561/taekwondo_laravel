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
        if (Schema::hasTable('bai_quyen')) {
            return;
        }

        // Create bai_quyen table
        Schema::create('bai_quyen', function (Blueprint $table) {
            $table->id();
            $table->string('ten_bai_quyen_vietnamese', 100);
            $table->string('ten_bai_quyen_english', 100);
            $table->string('ten_bai_quyen_korean', 100)->nullable();
            $table->string('cap_do', 50)->comment('Cơ bản, Trung cấp, Nâng cao');
            $table->text('mo_ta')->nullable();
            $table->integer('so_dong_tac')->nullable();
            $table->integer('thoi_gian_thuc_hien')->nullable()->comment('Thời gian tính bằng giây');
            $table->text('khoi_luong_ly_thuyet')->nullable();
            $table->timestamps();
        });

        // Create cap_dai_bai_quyen table
        Schema::create('cap_dai_bai_quyen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cap_dai_id')->constrained('cap_dai')->onDelete('cascade');
            $table->foreignId('bai_quyen_id')->constrained('bai_quyen')->onDelete('cascade');
            $table->enum('loai_quyen', ['bat_buoc'])->default('bat_buoc');
            $table->integer('thu_tu_uu_tien')->default(1);
            $table->timestamp('created_at')->useCurrent();
            
            $table->unique(['cap_dai_id', 'bai_quyen_id'], 'unique_cap_dai_bai_quyen');
        });

        // Create lich_su_thi_thang_cap_dai table
        Schema::create('lich_su_thi_thang_cap_dai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vo_sinh_id')->constrained('vo_sinh')->onDelete('cascade');
            $table->foreignId('bai_quyen_id')->constrained('bai_quyen')->onDelete('cascade');
            $table->foreignId('cap_dai_id')->constrained('cap_dai')->onDelete('cascade');
            $table->decimal('diem_so', 5, 2)->nullable();
            $table->enum('ket_qua', ['dat', 'khong_dat', 'xuat_sac'])->default('khong_dat');
            $table->date('ngay_thi');
            $table->text('ghi_chu')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_thi_thang_cap_dai');
        Schema::dropIfExists('cap_dai_bai_quyen');
        Schema::dropIfExists('bai_quyen');
    }
};

