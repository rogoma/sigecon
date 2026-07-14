<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderMultiYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_multi_years', function (Blueprint $table) {
            $table->integer('id')->autoIncrement(); // set auto-increment and primary key
            $table->integer('order_id');
            $table->smallInteger('year');
            $table->bigInteger('amount');
            $table->integer('creator_user_id');
            $table->integer('modifier_user_id')->nullable();
            $table->timestamps();
            // INDEXES
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade');
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
        Schema::dropIfExists('order_multi_years');
    }
}
