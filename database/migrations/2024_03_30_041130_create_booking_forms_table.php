<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('uuid')->autoIncrement();
            $table->string('bride');
            $table->string('groom');
            $table->string('bride_family_address');
            $table->string('bride_father_name');
            $table->string('bride_mother_name');
            $table->string('groom_family_address');
            $table->string('groom_father_name');
            $table->string('groom_mother_name');
            $table->dateTime('wedding_date');
            $table->dateTime('party_date');
            $table->string('party_name_place');
            $table->string('party_address');
            $table->string('image_design');
            $table->boolean('is_confirm')->default(false);
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
        Schema::dropIfExists('booking_forms');
    }
};