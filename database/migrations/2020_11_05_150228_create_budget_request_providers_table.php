<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetRequestProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_request_providers', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();    // set auto-increment and primary key
            $table->integer('order_id');
            $table->smallInteger('provider_id');
            $table->integer('creator_user_id');
            $table->integer('creator_dependency_id');
            $table->integer('modifier_user_id')->nullable();
            $table->timestamps();
            // INDEXES
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onUpdate('cascade');
            $table->foreign('creator_user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('creator_dependency_id')->references('id')->on('dependencies')->onUpdate('cascade');
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
        Schema::dropIfExists('budget_request_providers');
    }
}
