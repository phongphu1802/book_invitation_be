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
        Schema::create('order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('uuid')->autoIncrement();
            $table->unsignedBigInteger('order_uuid');
            $table->foreign('order_uuid')->references('uuid')->on('orders');
            $table->unsignedBigInteger('product_uuid');
            $table->foreign('product_uuid')->references('uuid')->on('products');
            $table->integer('quantity');
            $table->decimal('sub_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
};
