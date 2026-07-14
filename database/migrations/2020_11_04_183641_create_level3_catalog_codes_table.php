<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevel3CatalogCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level3_catalog_codes', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('level2_catalog_code_id');
            $table->integer('code');
            $table->string('description', 150);
            $table->timestamps();
            // INDEXES
            $table->foreign('level2_catalog_code_id')->references('id')->on('level2_catalog_codes')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level3_catalog_codes');
    }
}
