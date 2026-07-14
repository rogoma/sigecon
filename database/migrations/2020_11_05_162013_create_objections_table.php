<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objections', function (Blueprint $table) {
            $table->integer('id')->autoIncrement(); // set auto-increment and primary key
            $table->string('objection', 300);            
            $table->smallInteger('order_id');
            $table->smallInteger('order_state_id');
            $table->timestamps(); // update and create timestamps columns
            $table->integer('creator_user_id');
            $table->integer('creator_dependency_id');
            $table->integer('modifier_user_id')->nullable();
            $table->integer('modifier_dependency_id')->nullable();
            // INDEXES            
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade');
            $table->foreign('order_state_id')->references('id')->on('order_states')->onUpdate('cascade');
            $table->foreign('creator_user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('creator_dependency_id')->references('id')->on('dependencies')->onUpdate('cascade');
            $table->foreign('modifier_user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('modifier_dependency_id')->references('id')->on('dependencies')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('objections');
    }
}
