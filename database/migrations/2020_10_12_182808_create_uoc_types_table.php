<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUocTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uoc_types', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();	// set auto-increment and primary key
            $table->string('description', 150)->unique();	// unique index
            $table->timestamps();                           // update and create timestamps columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uoc_types');
    }
}
