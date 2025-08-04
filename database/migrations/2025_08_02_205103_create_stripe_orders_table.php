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
        Schema::create('stripe_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->integer('customer_id');
            $table->integer('subtotal');
            $table->integer('coupon_discount')->nullable();
            $table->integer('charge');
            $table->integer('total');
            $table->string('payment_method');
            $table->integer('status')->default(0);
            $table->string('company_name')->nullable();
            $table->string('street_address');
            $table->string('floor')->nullable();
            $table->string('city');
            $table->string('phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_orders');
    }
};
