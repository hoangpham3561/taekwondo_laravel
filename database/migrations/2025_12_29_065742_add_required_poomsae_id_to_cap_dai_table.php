<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // migration file
    public function up(): void
    {
        if (!Schema::hasTable('cap_dai')) {
            return;
        }

        if (Schema::hasColumn('cap_dai', 'required_poomsae_id')) {
            return;
        }

        Schema::table('cap_dai', function (Blueprint $table) {
            // Keep migration compatible with legacy schemas where foreign key types may differ.
            $table->unsignedBigInteger('required_poomsae_id')->nullable()->after('order_sequence');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('cap_dai') || !Schema::hasColumn('cap_dai', 'required_poomsae_id')) {
            return;
        }

        Schema::table('cap_dai', function (Blueprint $table) {
            $table->dropColumn('required_poomsae_id');
        });
    }
};
