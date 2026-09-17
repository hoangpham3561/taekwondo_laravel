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
        if (Schema::hasTable('ky_thi_thang_cap')) {
            return;
        }

        // Create ky_thi_thang_cap table
        Schema::create('ky_thi_thang_cap', function (Blueprint $table) {
            $table->id();
            $table->string('test_name', 100);
            $table->date('test_date')->nullable();
            $table->string('location', 255)->nullable();
            $table->foreignId('examiner_id')->nullable()->constrained('huan_luyen_vien');
            $table->foreignId('club_id')->nullable()->constrained('cau_lac_bo');
            $table->integer('max_participants')->nullable();
            $table->date('registration_deadline')->nullable();
            $table->decimal('test_fee', 10, 2)->default(0);
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->timestamps();
        });

        // Create dang_ky_thi table
        Schema::create('dang_ky_thi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->nullable()->constrained('ky_thi_thang_cap');
            $table->foreignId('user_id')->nullable()->constrained('vo_sinh');
            $table->foreignId('current_belt_id')->nullable()->constrained('cap_dai');
            $table->foreignId('target_belt_id')->nullable()->constrained('cap_dai');
            $table->timestamp('registration_date')->useCurrent();
            $table->enum('payment_status', ['paid', 'pending'])->default('pending');
            $table->enum('test_result', ['pass', 'fail', 'pending'])->default('pending');
            $table->decimal('score', 5, 2)->nullable();
            $table->text('examiner_notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // Create chung_chi table
        Schema::create('chung_chi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('vo_sinh');
            $table->foreignId('belt_level_id')->nullable()->constrained('cap_dai');
            $table->string('certificate_number', 50)->unique();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('issued_by', 100)->nullable();
            $table->string('certificate_image_url', 255)->nullable();
            $table->boolean('is_valid')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        // Create danh_gia_phan_hoi table
        Schema::create('danh_gia_phan_hoi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('vo_sinh');
            $table->foreignId('course_id')->nullable()->constrained('khoa_hoc');
            $table->foreignId('coach_id')->nullable()->constrained('huan_luyen_vien');
            $table->tinyInteger('rating')->nullable();
            $table->text('comment')->nullable();
            $table->enum('feedback_type', ['course', 'coach', 'facility', 'general'])->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_gia_phan_hoi');
        Schema::dropIfExists('chung_chi');
        Schema::dropIfExists('dang_ky_thi');
        Schema::dropIfExists('ky_thi_thang_cap');
    }
};

