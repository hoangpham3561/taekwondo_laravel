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
        Schema::create('bank_transfer_deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('package_id')->nullable()->index();
            $table->decimal('amount', 12, 2);
            $table->string('transfer_note', 100)->unique();
            $table->char('status', 1)->default('N')->comment('N: pending, Y: paid, C: canceled');
            $table->string('proof_image', 500)->nullable();
            $table->string('bank_code', 20)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->text('qr_url')->nullable();
            $table->string('bank_txn_ref', 100)->nullable()->index();
            $table->timestamp('paid_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transfer_deposits');
    }
};
