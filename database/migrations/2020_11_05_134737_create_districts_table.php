<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->smallInteger('coddist');
            $table->smallInteger('coddpto');
            $table->smallInteger('codreg');
            $table->string('subcreg', 3);
            $table->string('nomdist', 50);
            $table->timestamps();
            // INDEXES
            $table->unique(['coddist', 'coddpto']);
            $table->foreign('coddpto')->references('coddpto')->on('departments');
            $table->foreign(['codreg', 'subcreg'])->references(['codreg', 'subcreg'])->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
}
