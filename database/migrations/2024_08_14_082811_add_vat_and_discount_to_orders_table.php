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
        Schema::table('orders', function (Blueprint $table) {
			$table->decimal('vat_amount', 8, 2)->nullable()->after('total_amount');
			$table->decimal('discount_amount', 8, 2)->nullable()->after('vat_amount');
			$table->decimal('paid_amount', 8, 2)->nullable()->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
			$table->dropColumn(['vat_amount', 'discount_amount', 'paid_amount']);
        });
    }
};
