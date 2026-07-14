<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_permissions', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();    // set auto-increment and primary key
            $table->smallInteger('permission_id');
            $table->smallInteger('role_id');
            $table->timestamps();
            // INDEXES            
            $table->foreign('permission_id')
                  ->references('id')
                  ->on('permissions')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('role_id')
                  ->references('id')
                  ->on('roles')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_permissions');
    }
}
