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
        if (Schema::hasTable('vo_sinh')) {
            return;
        }

        Schema::create('vo_sinh', function (Blueprint $table) {
            $table->id();
            $table->string('ho_va_ten', 100)->comment('Họ và tên đầy đủ');
            $table->date('ngay_thang_nam_sinh')->comment('Ngày tháng năm sinh');
            $table->string('ma_hoi_vien', 50)->unique()->comment('Mã hội viên');
            $table->string('ma_clb', 20)->comment('Mã câu lạc bộ');
            $table->string('ma_don_vi', 20)->comment('Mã đơn vị');
            $table->integer('quyen_so')->comment('Quyền số');
            $table->foreignId('cap_dai_id')->constrained('cap_dai')->comment('Cấp đai hiện tại');
            $table->enum('gioi_tinh', ['Nam', 'Nữ'])->comment('Giới tính');
            $table->string('email', 100)->unique()->nullable();
            $table->string('phone', 15)->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_phone', 15)->nullable();
            $table->boolean('active_status')->default(true);
            $table->string('profile_image_url', 255)->nullable();
            $table->text('images')->nullable()->comment('Danh sách ảnh (JSON array)');
            $table->string('password', 255)->comment('Mật khẩu đăng nhập cho võ sinh');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vo_sinh');
    }
};

