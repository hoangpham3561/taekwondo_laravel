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
        if (Schema::hasTable('tin_tuc')) {
            return;
        }

        // Create tin_tuc table
        Schema::create('tin_tuc', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->text('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('huan_luyen_vien')->comment('ID của HLV viết bài');
            $table->string('featured_image_url', 255)->nullable();
            $table->text('images')->nullable()->comment('Danh sách ảnh (JSON array)');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // Create thu_vien table
        Schema::create('thu_vien', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('file_url', 500);
            $table->enum('file_type', ['image', 'video'])->default('image');
            $table->string('mime_type', 100)->nullable();
            $table->bigInteger('file_size')->nullable()->comment('File size in bytes');
            $table->foreignId('club_id')->nullable()->constrained('cau_lac_bo');
            $table->foreignId('branch_id')->nullable()->constrained('chi_nhanh');
            $table->unsignedBigInteger('created_by')->nullable()->comment('User ID who uploaded the media');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create tin_nhan_lien_he table
        Schema::create('tin_nhan_lien_he', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('phone', 15)->nullable();
            $table->string('subject', 200)->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'read', 'replied', 'closed'])->default('new');
            $table->timestamp('created_at')->useCurrent();
        });

        // Create thanh_toan table
        Schema::create('thanh_toan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('vo_sinh');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date')->nullable();
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            $table->enum('status', ['paid', 'pending', 'late'])->default('paid');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        // Create thang_cap_dai table
        Schema::create('thang_cap_dai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('vo_sinh');
            $table->foreignId('from_belt_id')->nullable()->constrained('cap_dai');
            $table->foreignId('to_belt_id')->nullable()->constrained('cap_dai');
            $table->date('promotion_date')->nullable();
            $table->foreignId('coach_id')->nullable()->constrained('huan_luyen_vien');
            $table->decimal('test_score', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // Create diem_danh table
        Schema::create('diem_danh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('vo_sinh');
            $table->foreignId('course_id')->nullable()->constrained('khoa_hoc');
            $table->date('attendance_date')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // Create danh_gia_hoc_vien table
        Schema::create('danh_gia_hoc_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('vo_sinh');
            $table->foreignId('coach_id')->nullable()->constrained('huan_luyen_vien');
            $table->foreignId('course_id')->nullable()->constrained('khoa_hoc');
            $table->date('evaluation_date')->nullable();
            $table->decimal('technique_score', 3, 1)->nullable();
            $table->decimal('attitude_score', 3, 1)->nullable();
            $table->decimal('progress_score', 3, 1)->nullable();
            $table->decimal('overall_score', 3, 1)->nullable();
            $table->text('comments')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // Create su_kien table
        Schema::create('su_kien', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->enum('event_type', ['tournament', 'seminar', 'graduation', 'social', 'other'])->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('location', 255)->nullable();
            $table->foreignId('club_id')->nullable()->constrained('cau_lac_bo');
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->timestamps();
        });

        // Create thong_bao table
        Schema::create('thong_bao', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('content')->nullable();
            $table->enum('type', ['general', 'payment', 'event', 'course', 'promotion'])->nullable();
            $table->enum('target_audience', ['all', 'students', 'coaches', 'HLV'])->nullable();
            $table->foreignId('club_id')->nullable()->constrained('cau_lac_bo');
            $table->boolean('is_urgent')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // Create goi_hoc_phi table
        Schema::create('goi_hoc_phi', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('duration_months')->nullable();
            $table->integer('classes_per_week')->nullable();
            $table->foreignId('club_id')->nullable()->constrained('cau_lac_bo');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create chi_tiet_thanh_toan table
        Schema::create('chi_tiet_thanh_toan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained('thanh_toan');
            $table->foreignId('tuition_package_id')->nullable()->constrained('goi_hoc_phi');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'card', 'other'])->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // Create tien_trinh_hoc_tap table
        Schema::create('tien_trinh_hoc_tap', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('vo_sinh');
            $table->foreignId('course_id')->nullable()->constrained('khoa_hoc');
            $table->date('lesson_date')->nullable();
            $table->text('lesson_content')->nullable();
            $table->text('skills_learned')->nullable();
            $table->text('homework')->nullable();
            $table->text('coach_notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tien_trinh_hoc_tap');
        Schema::dropIfExists('chi_tiet_thanh_toan');
        Schema::dropIfExists('goi_hoc_phi');
        Schema::dropIfExists('thong_bao');
        Schema::dropIfExists('su_kien');
        Schema::dropIfExists('danh_gia_hoc_vien');
        Schema::dropIfExists('diem_danh');
        Schema::dropIfExists('thang_cap_dai');
        Schema::dropIfExists('thanh_toan');
        Schema::dropIfExists('tin_nhan_lien_he');
        Schema::dropIfExists('thu_vien');
        Schema::dropIfExists('tin_tuc');
    }
};

