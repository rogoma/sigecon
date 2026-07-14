<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_awards', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();    // set auto-increment and primary key
            $table->integer('order_id');
            $table->smallInteger('batch')->nullable();
            $table->smallInteger('item_number')->nullable();
            $table->integer('level5_catalog_code_id');
            $table->string('technical_specifications', 100);
            $table->smallInteger('order_presentation_id');
            $table->smallInteger('order_measurement_unit_id');
            $table->integer('quantity');
            $table->integer('unit_price');
            $table->bigInteger('total_amount');
            $table->integer('creator_user_id');
            $table->integer('modifier_user_id')->nullable();
            $table->timestamps();
            // INDEXES
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade');
            $table->foreign('level5_catalog_code_id')->references('id')->on('level5_catalog_codes')->onUpdate('cascade');
            $table->foreign('order_presentation_id')->references('id')->on('order_presentations')->onUpdate('cascade');
            $table->foreign('order_measurement_unit_id')->references('id')->on('order_measurement_units')->onUpdate('cascade');
            $table->foreign('creator_user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('modifier_user_id')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_awards');
    }
}
