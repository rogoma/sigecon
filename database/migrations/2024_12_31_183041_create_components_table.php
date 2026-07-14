<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('components', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();	// set auto-increment and primary key
            $table->string('code', 10);	
            $table->string('description', 200)->unique();	// unique index
            $table->timestamps();

            // INDEXES            
            $table->foreign('comp_type_id')->references('id')->on('comp_type_id')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('components');
    }
}