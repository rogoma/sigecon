<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersOrderStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_order_states', function (Blueprint $table) {
            $table->integer('id')->autoIncrement(); // set auto-increment and primary key
            $table->integer('order_id');
            $table->smallInteger('order_state_id');
            $table->integer('creator_user_id');
            $table->timestamps();                               // update and create timestamps columns
            // INDEXES            
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('order_state_id')->references('id')->on('order_states');
            $table->foreign('creator_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_order_states');
    }
}
