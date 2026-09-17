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
        if (Schema::hasTable('phu_huynh')) {
            return;
        }

        // Create phu_huynh table
        Schema::create('phu_huynh', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100)->nullable();
            $table->string('phone', 15)->nullable();
            $table->enum('relationship', ['father', 'mother', 'guardian', 'other'])->nullable();
            $table->string('address', 255)->nullable();
            $table->boolean('emergency_contact')->default(false);
            $table->timestamps();
        });

        // Create hoc_vien_phu_huynh table
        Schema::create('hoc_vien_phu_huynh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained('vo_sinh');
            $table->foreignId('parent_id')->nullable()->constrained('phu_huynh');
            $table->boolean('is_primary')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoc_vien_phu_huynh');
        Schema::dropIfExists('phu_huynh');
    }
};

