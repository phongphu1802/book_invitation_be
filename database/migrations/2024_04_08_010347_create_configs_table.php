<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ConfigEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->unsignedBigInteger('uuid')->autoIncrement();
            $table->string('key');
            $table->text('value');
            $table->enum('type', [ConfigEnum::PUBLIC ->value, ConfigEnum::SYSTEM->value]);
            $table->enum('datatype', [ConfigEnum::BOOLEAN->value, ConfigEnum::TEXT->value, ConfigEnum::IMAGE->value, ConfigEnum::IMAGES->value, ConfigEnum::NUMBER->value]);
            $table->string('description');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
};