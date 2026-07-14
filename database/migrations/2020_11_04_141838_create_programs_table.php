<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();	// set auto-increment and primary key
            $table->smallInteger('program_type_id');
            $table->string('description', 200)->unique();	// unique index
            $table->smallInteger('code');
            $table->timestamps();
            // INDEXES
            $table->foreign('program_type_id')->references('id')->on('program_types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programs');
    }
}
