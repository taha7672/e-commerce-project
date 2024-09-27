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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code');
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->integer('discount_percentage')->default(0);
            $table->double('discount_amount',8,2)->default(0.00);
            $table->timestamp('expiry_date');  
            $table->double('minimum_order_amount',8,2);
            $table->boolean('one_time_use');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
