<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->autoIncrement(); // set auto-increment and primary key
            $table->string('name', 150);
            $table->string('lastname', 150);
            $table->string('document', 15)->unique();
            $table->string('email', 100)->nullable();  // se admite null
            $table->string('password', 100);
            $table->smallInteger('role_id');
            $table->smallInteger('dependency_id');
            $table->smallInteger('position_id');
            $table->smallInteger('state');
            $table->timestamps();  // update and create timestamps columns
            // INDEXES
            $table->foreign('dependency_id')->references('id')->on('dependencies')->onUpdate('cascade');
            $table->foreign('position_id')->references('id')->on('positions')->onUpdate('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
