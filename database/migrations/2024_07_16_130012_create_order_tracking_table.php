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
        Schema::create('order_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('status');
            $table->string('location');
            $table->timestamp('status_updated_at')->nullable();
            $table->timestamp('expected_delivery_at')->nullable(); 
            $table->string('tracking_number')->nullable(); 
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->timestamps();
            // Foreign key constraint
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('shipping_companies')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_tracking');
    }
};
