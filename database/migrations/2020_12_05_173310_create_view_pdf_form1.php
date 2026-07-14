<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateViewPdfForm1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement ("CREATE VIEW public.vista_pdf_form1  AS SELECT orders.id,
            orders.number,
            orders.responsible,
            dependencies.description AS dependencies_description,
            modalities.code AS modalities_code,
            orders.description AS orders_description,
            orders.begin_date,
            vista_og_final.code_supexp,
            program_types.code AS pt,
            programs.code AS pc,
            sub_programs.activity_code,
            funding_sources.code AS ff,
            financial_organisms.code AS fo,
            orders.total_amount,
            orders.total_amount AS total_amount_year
        FROM orders
        JOIN sub_programs ON orders.sub_program_id = sub_programs.id
        JOIN dependencies ON orders.dependency_id = dependencies.id
        JOIN funding_sources ON orders.funding_source_id = funding_sources.id
        JOIN modalities ON orders.modality_id = modalities.id
        JOIN programs ON sub_programs.program_id = programs.id
        JOIN program_types ON programs.program_type_id = program_types.id
        JOIN financial_organisms ON orders.financial_organism_id =
        financial_organisms.id
        JOIN vista_og_final ON orders.expenditure_object_id = vista_og_final.id;");        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_pdf_form1');
    }
}
