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
            $table->unsignedBigInteger('coupon_id')->nullable()->change();
            $table->unsignedBigInteger('shipping_address_id')->nullable();
            $table->unsignedBigInteger('billing_address_id')->nullable();

            $table->foreign('shipping_address_id')->references('id')->on('shipping_addresses')->onDelete('set null');
            $table->foreign('billing_address_id')->references('id')->on('billing_addresses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('coupon_id')->nullable(false)->change();

            $table->dropForeign(['shipping_address_id']);
            $table->dropForeign(['billing_address_id']);

            $table->unsignedBigInteger('shipping_address_id')->nullable(false);
            $table->unsignedBigInteger('billing_address_id')->nullable(false);
        });
    }
};
