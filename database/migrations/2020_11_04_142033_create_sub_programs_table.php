<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_programs', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();	// set auto-increment and primary key
            $table->smallInteger('program_id');
            $table->string('description', 150);
            $table->smallInteger('activity_code');
            $table->string('proyecto', 10);
            $table->smallInteger('program_measurement_unit_id');
            $table->timestamps();
            // INDEXES                        
            $table->foreign('program_id')->references('id')->on('programs')->onUpdate('cascade');
            $table->foreign('program_measurement_unit_id')->references('id')->on('program_measurement_units')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_programs');
    }
}
