<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCertificationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_certification_details', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('item_certification_id');
            $table->smallInteger('rubro_id');
            $table->decimal('quantity', 15, 2); // cantidad medida (Actual) en esta acta, para este rubro
            $table->timestamps();

            $table->foreign('item_certification_id')->references('id')->on('item_certifications')->onDelete('cascade');
            $table->foreign('rubro_id')->references('id')->on('rubros')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_certification_details');
    }
}
