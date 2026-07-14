<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimeseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simese', function (Blueprint $table) {
            $table->integer('id')->autoIncrement(); // set auto-increment and primary key
            $table->integer('simese');    
            $table->smallInteger('year');
            $table->smallInteger('order_id');
            $table->smallInteger('order_state_id');
            $table->integer('creator_user_id')->nullable();	// se admite null            
            $table->smallInteger('dependency_id')->nullable();	// se admite null            
            $table->timestamps();                               // update and create timestamps columns
            // INDEXES            
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade');
            $table->foreign('order_state_id')->references('id')->on('order_states')->onUpdate('cascade');
            $table->foreign('creator_user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('dependency_id')->references('id')->on('dependencies')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simese_order_states');
    }
}
