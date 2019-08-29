<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClothingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clothings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->integer('brand_id');
            $table->integer('category_id');
            $table->string('color', 32)->nullable();
            $table->integer('size')->default(1);
            $table->integer('gender')->default(1);
            $table->integer('age')->default(1);
            $table->string('material', 128)->nullable();
            $table->string('country', 64)->nullable();
            $table->boolean('active')->default(true);
            $table->double('price')->default(0.0);
            $table->integer('discount')->default(0);
            $table->text('description')->nullable();
            $table->text('photo')->nullable();
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
        Schema::dropIfExists('clothings');
    }
}
