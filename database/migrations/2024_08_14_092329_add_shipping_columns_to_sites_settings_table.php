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
			$table->decimal('shipping_amount', 8, 2)->nullable()->after('vat_amount');
			$table->decimal('free_shipping_threshold', 8, 2)->nullable()->after('shipping_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites_settings', function (Blueprint $table) {
			$table->dropColumn(['shipping_amount', 'free_shipping_threshold']);
        });
    }
};
