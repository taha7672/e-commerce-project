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
        Schema::table('billing_addresses', function (Blueprint $table) {
			$table->string('first_name', 255)->after('id'); // Adjust 'after' position as necessary
			$table->string('last_name', 255)->after('first_name');
			$table->string('phone', 20)->after('last_name');
			$table->string('email', 255)->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billing_addresses', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'phone', 'email']);
        });
    }
};
