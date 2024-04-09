<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('uuid')->autoIncrement();
            $table->unsignedBigInteger('cart_uuid');
            $table->foreign('cart_uuid')->references('uuid')->on('carts');
            $table->unsignedBigInteger('product_uuid');
            $table->foreign('product_uuid')->references('uuid')->on('products');
            $table->integer('quantity');
            $table->decimal('sub_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
};
