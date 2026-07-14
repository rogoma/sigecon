<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modalities', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();	// set auto-increment and primary key
            $table->string('description', 150);	// unique index
            $table->string('code', 10);	// unique index            
            $table->string('modality_type', 100);            
            $table->smallInteger('dncp_verification');
            $table->smallInteger('dncp_objections_verification');
            $table->smallInteger('press_publication');
            $table->smallInteger('portal_difusion');
            $table->smallInteger('inquiries_reception');
            $table->smallInteger('addendas_verification');            
            $table->smallInteger('addenda_publication');          
            $table->smallInteger('clarifications_publication');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modalities');
    }
}
