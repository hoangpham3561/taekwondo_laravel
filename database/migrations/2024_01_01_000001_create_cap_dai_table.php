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
        if (! Schema::hasTable('cap_dai')) {
            Schema::create('cap_dai', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50)->unique();
                $table->string('color', 20)->nullable();
                $table->integer('order_sequence')->nullable();
                $table->string('required_poomsae_code', 20)->nullable()->comment('Mã bài quyền bắt buộc cho cấp đai này');
                $table->string('required_poomsae_name', 100)->nullable()->comment('Tên bài quyền bắt buộc');
                $table->text('description')->nullable();
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
        Schema::dropIfExists('cap_dai');
    }
};

