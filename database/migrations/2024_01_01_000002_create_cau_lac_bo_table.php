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
        if (! Schema::hasTable('cau_lac_bo')) {
            Schema::create('cau_lac_bo', function (Blueprint $table) {
                $table->id();
                $table->string('club_code', 20)->unique();
                $table->string('name', 100);
                $table->string('address', 255)->nullable();
                $table->string('phone', 20)->nullable();
                $table->string('email', 100)->nullable();
                $table->unsignedBigInteger('head_coach_id')->nullable();
                $table->text('description')->nullable();
                $table->string('logo_url', 255)->nullable();
                $table->text('images')->nullable()->comment('Danh sách ảnh (JSON array)');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cau_lac_bo');
    }
};

