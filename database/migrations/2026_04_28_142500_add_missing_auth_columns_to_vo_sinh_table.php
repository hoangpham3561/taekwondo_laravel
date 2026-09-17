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
        if (!Schema::hasTable('vo_sinh')) {
            return;
        }

        Schema::table('vo_sinh', function (Blueprint $table) {
            if (!Schema::hasColumn('vo_sinh', 'api_token')) {
                $table->string('api_token', 80)->nullable()->unique()->after('password');
            }

            if (!Schema::hasColumn('vo_sinh', 'remember_token')) {
                $table->rememberToken()->after('api_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('vo_sinh')) {
            return;
        }

        Schema::table('vo_sinh', function (Blueprint $table) {
            if (Schema::hasColumn('vo_sinh', 'remember_token')) {
                $table->dropRememberToken();
            }

            if (Schema::hasColumn('vo_sinh', 'api_token')) {
                $table->dropColumn('api_token');
            }
        });
    }
};
