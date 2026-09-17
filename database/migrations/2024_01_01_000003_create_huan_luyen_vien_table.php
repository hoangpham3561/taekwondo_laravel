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
        if (! Schema::hasTable('huan_luyen_vien')) {
            Schema::create('huan_luyen_vien', function (Blueprint $table) {
                $table->id();
                $table->string('ma_hoi_vien', 50)->unique()->comment('Mã hội viên HLV');
                $table->string('ho_va_ten', 100)->comment('Họ và tên đầy đủ');
                $table->date('ngay_thang_nam_sinh')->comment('Ngày tháng năm sinh');
                $table->string('ma_clb', 20)->comment('Mã câu lạc bộ');
                $table->string('ma_don_vi', 20)->comment('Mã đơn vị');
                $table->integer('quyen_so')->comment('Quyền số');
                $table->foreignId('cap_dai_id')->constrained('cap_dai')->comment('Cấp đai hiện tại');
                $table->enum('gioi_tinh', ['Nam', 'Nữ'])->comment('Giới tính');
                $table->string('photo_url', 255)->nullable();
                $table->text('images')->nullable()->comment('Danh sách ảnh (JSON array)');
                $table->string('phone', 15)->nullable();
                $table->string('email', 100)->nullable();
                $table->string('password', 255)->nullable();
                $table->enum('role', ['owner', 'admin'])->default('admin')->comment('Owner: quyền cao nhất. Admin: được owner quản lý');
                $table->integer('experience_years')->nullable();
                $table->string('specialization', 100)->nullable();
                $table->text('bio')->nullable();
                $table->text('address')->nullable();
                $table->string('emergency_contact_name', 100)->nullable();
                $table->string('emergency_contact_phone', 15)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Add foreign key for head_coach_id in cau_lac_bo table
        Schema::table('cau_lac_bo', function (Blueprint $table) {
            $table->foreign('head_coach_id')->references('id')->on('huan_luyen_vien')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cau_lac_bo', function (Blueprint $table) {
            $table->dropForeign(['head_coach_id']);
        });
        Schema::dropIfExists('huan_luyen_vien');
    }
};

