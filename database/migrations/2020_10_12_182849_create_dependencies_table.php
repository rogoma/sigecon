<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDependenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependencies', function (Blueprint $table) {
            $table->integer('id')->autoIncrement(); // set auto-increment and primary key
            $table->string('description', 150);
            $table->smallInteger('dependency_type_id');
            $table->smallInteger('uoc_type_id')->nullable();	// se admite null
            $table->smallInteger('uoc_number')->nullable();		// se admite null
            $table->smallInteger('sicp')->nullable();		// se admite null
            $table->integer('superior_dependency_id')->nullable();	// se admite null            
            $table->timestamps();                               // update and create timestamps columns
            
            // INDEXES
            $table->foreign('dependency_type_id')->references('id')->on('dependency_types')->onUpdate('cascade');
            $table->foreign('uoc_type_id')->references('id')->on('uoc_types')->onUpdate('cascade');
            $table->foreign('superior_dependency_id')->references('id')->on('dependencies')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dependencies');
    }
}
