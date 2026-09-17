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
        // Create chi_nhanh table
        if (!Schema::hasTable('chi_nhanh')) {
            Schema::create('chi_nhanh', function (Blueprint $table) {
                $table->id();
                $table->foreignId('club_id')->constrained('cau_lac_bo');
                $table->string('branch_code', 20)->unique();
                $table->string('name', 100);
                $table->string('address', 255)->nullable();
                $table->string('phone', 20)->nullable();
                $table->string('email', 100)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Create quan_ly_chi_nhanh table
        if (!Schema::hasTable('quan_ly_chi_nhanh')) {
            Schema::create('quan_ly_chi_nhanh', function (Blueprint $table) {
                $table->id();
                $table->foreignId('branch_id')->constrained('chi_nhanh');
                $table->foreignId('manager_id')->constrained('huan_luyen_vien');
                $table->enum('role', ['main_manager', 'assistant_manager'])->default('main_manager');
                $table->boolean('is_active')->default(true);
                $table->timestamp('assigned_at')->useCurrent();
                
                $table->unique(['branch_id', 'manager_id'], 'unique_branch_manager');
            });
        }

        // Create tro_giang_chi_nhanh table
        if (!Schema::hasTable('tro_giang_chi_nhanh')) {
            Schema::create('tro_giang_chi_nhanh', function (Blueprint $table) {
                $table->id();
                $table->foreignId('branch_id')->constrained('chi_nhanh');
                $table->foreignId('assistant_id')->constrained('huan_luyen_vien');
                $table->boolean('is_active')->default(true);
                $table->timestamp('assigned_at')->useCurrent();
                
                $table->unique(['branch_id', 'assistant_id'], 'unique_branch_assistant');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tro_giang_chi_nhanh');
        Schema::dropIfExists('quan_ly_chi_nhanh');
        Schema::dropIfExists('chi_nhanh');
    }
};

