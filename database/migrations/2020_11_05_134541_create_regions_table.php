<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->smallInteger('codreg');
            $table->string('subcreg', 3);
            $table->string('nomreg', 25);
            $table->timestamps();
            // INDEXES
            $table->unique(['codreg', 'subcreg']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regions');
    }
}
