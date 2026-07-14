<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenditureObjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenditure_objects', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement(); // set auto-increment and primary key
            $table->tinyInteger('code')->unique();
            $table->string('description', 150);
            $table->string('alias', 100);
            $table->smallInteger('level');
            $table->smallInteger('financial_level_id')->nullable();	// se admite null
            $table->smallInteger('superior_expenditure_object_id')->nullable();	// se admite null
            $table->timestamps();                               // update and create timestamps columns
            // INDEXES
            $table->foreign('financial_level_id')->references('id')->on('financial_levels')->onUpdate('cascade');
            $table->foreign('superior_expenditure_object_id')->references('id')->on('expenditure_objects')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenditure_objects');
    }
}
