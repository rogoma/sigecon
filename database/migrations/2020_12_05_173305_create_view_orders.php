<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateViewOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement ("CREATE VIEW public.vista_orders  AS SELECT orders.id AS order_id,
            orders.dependency_id,
            dependencies.description AS dependency,
            dependencies.dependency_type_id,
            dependencies.uoc_type_id,
            dependencies.uoc_number,
            dependencies.sicp,
            dependencies.superior_dependency_id,
            orders.responsible,
            orders.modality_id,
            modalities.description AS modality,
            modalities.code AS modality_code,
            modalities.modality_type,
            orders.dncp_pac_id,
            orders.begin_date,
            orders.sub_program_id,
            sub_programs.description AS sub_program,
            sub_programs.activity_code,
            sub_programs.proyecto,
            sub_programs.program_id,
            programs.description AS program,
            programs.code AS program_code,
            programs.program_type_id,
            program_types.description AS program_type,
            program_types.code AS program_type_code,
            sub_programs.program_measurement_unit_id,
            program_measurement_units.description AS program_measurement_unit,
            orders.funding_source_id,
            funding_sources.code AS ff_code,
            funding_sources.description AS ff,
            orders.financial_organism_id,
            financial_organisms.description AS of,
            financial_organisms.code AS of_code,
            orders.total_amount,
            orders.description AS order_description,
            orders.ad_referendum,
            orders.plurianualidad,
            orders.system_awarded_by,
            vista_og_final.code_supexp,
            vista_og_final.code AS exp_obj_code,
            orders.fonacide,
            orders.catalogs_technical_annexes,
            orders.alternative_offers,
            orders.open_contract,
            orders.period_time,
            orders.manufacturer_authorization,
            orders.financial_advance_percentage_amount,
            orders.technical_specifications,
            orders.samples,
            orders.delivery_plan,
            orders.evaluation_committee_proposal,
            orders.payment_conditions,
            orders.contract_guarantee,
            orders.product_guarantee,
            orders.contract_administrator,
            orders.contract_validity,
            orders.additional_technical_documents,
            orders.additional_qualified_documents,
            orders.price_sheet,
            orders.property_title,
            orders.magnetic_medium,
            orders.referring_person_data,
            orders.form4_city,
            orders.form4_date,
            orders.dncp_resolution_number,
            orders.dncp_resolution_date,
            orders.urgency_state,
            row_number() OVER(ORDER BY orders.id) AS nro,
            orders.number,
            orders.actual_state 
        FROM orders
        JOIN dependencies ON orders.dependency_id = dependencies.id
        JOIN modalities ON orders.modality_id = modalities.id
        JOIN sub_programs ON orders.sub_program_id = sub_programs.id
        JOIN programs ON sub_programs.program_id = programs.id
        JOIN program_measurement_units ON
        sub_programs.program_measurement_unit_id = program_measurement_units.id
        JOIN program_types ON programs.program_type_id = program_types.id
        JOIN funding_sources ON orders.funding_source_id = funding_sources.id
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
        Schema::dropIfExists('view_orders');
    }
}
