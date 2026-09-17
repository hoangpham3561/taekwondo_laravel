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
        if (!Schema::hasTable('vo_sinh') || Schema::hasColumn('vo_sinh', 'remember_token')) {
            return;
        }

        Schema::table('vo_sinh', function (Blueprint $table) {
            $table->rememberToken()->after('api_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('vo_sinh') || !Schema::hasColumn('vo_sinh', 'remember_token')) {
            return;
        }

        Schema::table('vo_sinh', function (Blueprint $table) {
            $table->dropRememberToken();
        });
    }
};
