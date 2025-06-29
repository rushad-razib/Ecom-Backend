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
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->integer('customer_id');
            $table->string('fname');
            $table->string('lname');
            $table->string('email');
            $table->integer('country_id');
            $table->integer('city_id')->nullable();
            $table->string('address');
            $table->integer('zip');
            $table->string('phone');
            $table->string('company')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};
