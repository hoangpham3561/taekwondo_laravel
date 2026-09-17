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
        // Create khoa_hoc table
        if (!Schema::hasTable('khoa_hoc')) {
            Schema::create('khoa_hoc', function (Blueprint $table) {
                $table->id();
                $table->string('title', 100);
                $table->text('description')->nullable();
                $table->enum('level', ['beginner', 'intermediate', 'advanced'])->nullable();
                $table->enum('quarter', ['Q1', 'Q2', 'Q3', 'Q4'])->nullable();
                $table->integer('year')->nullable();
                $table->foreignId('coach_id')->nullable()->constrained('huan_luyen_vien');
                $table->foreignId('club_id')->nullable()->constrained('cau_lac_bo');
                $table->foreignId('branch_id')->nullable()->constrained('chi_nhanh');
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->integer('current_students')->default(0);
                $table->string('image_url', 255)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Create dang_ky_hoc table
        if (!Schema::hasTable('dang_ky_hoc')) {
            Schema::create('dang_ky_hoc', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('vo_sinh');
                $table->foreignId('course_id')->nullable()->constrained('khoa_hoc');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->timestamp('enrolled_at')->useCurrent();
                $table->timestamp('approved_at')->nullable();
                $table->text('notes')->nullable();
            });
        }

        // Create lich_hoc table
        if (!Schema::hasTable('lich_hoc')) {
            Schema::create('lich_hoc', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->nullable()->constrained('khoa_hoc');
                $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])->nullable();
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->string('location', 100)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_hoc');
        Schema::dropIfExists('dang_ky_hoc');
        Schema::dropIfExists('khoa_hoc');
    }
};

