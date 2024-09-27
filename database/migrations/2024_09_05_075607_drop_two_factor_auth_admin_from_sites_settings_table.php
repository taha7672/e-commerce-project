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
        Schema::table('sites_settings', function (Blueprint $table) {
            $table->dropColumn('two_factor_auth_admin');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites_settings', function (Blueprint $table) {
            $table->boolean('two_factor_auth_admin')->default(false)->after('two_factor_auth_user');

        });
    }
};
