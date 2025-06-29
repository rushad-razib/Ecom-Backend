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
            $table->integer('customer_id');
            $table->integer('total');
            $table->integer('subtotal');
            $table->integer('charge');
            $table->integer('discount')->nullable();
            $table->string('fname');
            $table->string('lname');
            $table->string('email');
            $table->integer('country');
            $table->integer('city');
            $table->string('address');
            $table->integer('zip');
            $table->string('company');
            $table->string('info')->nullable();
            $table->integer('phone');
            $table->string('ship_fname')->nullable();
            $table->string('ship_lname')->nullable();
            $table->integer('ship_country')->nullable();
            $table->integer('ship_city')->nullable();
            $table->integer('ship_zip')->nullable();
            $table->string('ship_address')->nullable();
            $table->integer('ship_phone')->nullable();
            $table->string('ship_company')->nullable();
            $table->string('ship_email')->nullable();
            $table->integer('ship_check')->nullable();
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
