<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateViewOg1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW public.vista_og1 AS SELECT expenditure_objects.id,
                 expenditure_objects.code,
                 expenditure_objects.description
          FROM expenditure_objects
          WHERE expenditure_objects.description = upper(expenditure_objects.description);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_og1');
    }
}
