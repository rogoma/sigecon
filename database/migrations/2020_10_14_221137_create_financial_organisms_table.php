<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialOrganismsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_organisms', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();    // set auto-increment and primary key
            $table->string('description', 150)->unique();   // unique index
            $table->tinyInteger('code')->unique();
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
        Schema::dropIfExists('financial_organisms');
    }
}
