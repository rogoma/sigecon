<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Fecha tope de consultas, se admite null
            $table->date('queries_deadline')->nullable();
        });
        Schema::table('queries', function (Blueprint $table) {
            $table->date('query_date');
            $table->boolean('answered')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('queries_deadline');
        });
        Schema::table('queries', function (Blueprint $table) {
            $table->dropColumn('query_date');
            $table->dropColumn('answered');
        });
    }
}
