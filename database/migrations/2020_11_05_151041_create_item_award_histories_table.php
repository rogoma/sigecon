<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemAwardHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_award_histories', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();    // set auto-increment and primary key
            $table->integer('item_id');
            $table->integer('dncp_pac_id')->nullable();
            $table->integer('budget_request_provider_id')->nullable();
            $table->integer('amount');
            $table->integer('creator_dependency_id');
            $table->timestamps();
            $table->integer('creator_user_id');
            $table->integer('modifier_user_id')->nullable();
            // INDEXES
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade');
            $table->foreign('budget_request_provider_id')->references('id')->on('budget_request_providers')->onUpdate('cascade');
            $table->foreign('creator_dependency_id')->references('id')->on('dependencies')->onUpdate('cascade');
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
        Schema::dropIfExists('item_award_histories');
    }
}
