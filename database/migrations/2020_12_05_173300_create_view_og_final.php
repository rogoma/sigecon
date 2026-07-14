<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateViewOgFinal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement ("CREATE VIEW public.vista_og_final  AS SELECT DISTINCT expenditure_objects.id,
                 expenditure_objects.code,
                 expenditure_objects.description,
                 vista_og1.code AS code_supexp,
                 vista_og1.description AS description_supexp
          FROM expenditure_objects
               LEFT JOIN vista_og1 ON expenditure_objects.superior_expenditure_object_id
                = vista_og1.id
          ORDER BY expenditure_objects.code;");
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_og_final');
    }
}
