<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectionsResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objections_responses', function (Blueprint $table) {
            $table->integer('id')->autoIncrement(); // set auto-increment and primary key
            $table->string('response', 300);
            $table->integer('objection_id')->nullable();	// se admite null                        
            $table->timestamps();   // update and create timestamps columns
            $table->integer('creator_user_id');
            $table->integer('creator_dependency_id');
            $table->integer('modifier_user_id')->nullable();
            $table->integer('modifier_dependency_id')->nullable();
            // INDEXES
            $table->foreign('objection_id')->references('id')->on('objections')->onUpdate('cascade');
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
        Schema::dropIfExists('objections_responses');
    }
}
