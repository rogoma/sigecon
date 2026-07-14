<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->integer('id')->autoIncrement(); // set auto-increment and primary key
            $table->string('description',300);
            $table->integer('iddncp');
            $table->string('linkdncp',300);
            $table->string('number_year',7);
            $table->smallInteger('year_adj');
            $table->date('sign_date');
            $table->smallInteger('provider_id');
            $table->smallInteger('actual_state');   // Estado actual
            $table->smallInteger('modality_id');
            $table->smallInteger('financial_organism_id');
            $table->smallInteger('contract_type_id');
            $table->date('contract_begin_date');
            $table->date('contract_end_date');
            $table->bigInteger('total_amount');

            $table->date('advance_validity');// fecha vencimiento de anticipo
            $table->smallInteger('advance_dld');//cant. días antes del vcto del anticipo
            $table->bigInteger('advance_amount');//monto del anticipo

            $table->date('fidelity_validity');// fecha vencimiento póliza fidelidad
            $table->smallInteger('fidelity_dld');//cant. días antes del vcto póliza fidelidad
            $table->bigInteger('fidelity_amount');//monto de la póliza fidelidad

            $table->date('accidents_validity');// fecha vencimiento póliza accidentes
            $table->smallInteger('accidents_dld');//cant. días antes del vcto póliza accidentes
            $table->bigInteger('accidents_amount');//monto de la póliza accidentes

            $table->date('risks_validity');// fecha vencimiento póliza todo riesgo
            $table->smallInteger('risks_dld');//cant. días antes del vcto póliza todo riesgo
            $table->bigInteger('risks_amount');//monto de la póliza todo riesgo

            $table->date('civil_resp_validity');// fecha vencimiento póliza resp. civil
            $table->smallInteger('civil_resp_dld');//cant. días antes del vcto póliza resp. civil
            $table->bigInteger('civil_resp_amount');//monto de la póliza resp. civil

            $table->string('comments', 300);
            $table->integer('creator_user_id');
            $table->integer('modifier_user_id')->nullable();
            $table->timestamps();

            // INDEXES
            //$table->foreign('dependency_id')->references('id')->on('dependencies')->onUpdate('cascade');
            $table->foreign('modality_id')->references('id')->on('modalities')->onUpdate('cascade');
            $table->foreign('financial_organism_id')->references('id')->on('financial_organisms')->onUpdate('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onUpdate('cascade');
            $table->foreign('contract_types_id')->references('id')->on('contract_types')->onUpdate('cascade');
            $table->foreign('creator_user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('modifier_user_id')->references('id')->on('users')->onUpdate('cascade');
            

            // $table->integer('number')->nullable();
            // $table->integer('dependency_id');
            // $table->date('begin_date')->nullable(); // se admite null
            // $table->smallInteger('funding_source_id');
            // $table->boolean('ad_referendum');
            // $table->boolean('plurianualidad');
            // $table->enum('system_awarded_by', ['LOTE', 'ÍTEM', 'TOTAL', 'COMBINADO']);
            // $table->smallInteger('expenditure_object_id');
            // $table->boolean('fonacide');
            // $table->boolean('catalogs_technical_annexes');
            // $table->boolean('alternative_offers');
            // $table->boolean('open_contract');
            // $table->string('period_time', 50);
            // $table->boolean('manufacturer_authorization');
            // $table->boolean('financial_advance_percentage_amount');
            // $table->string('technical_specifications', 100);
            // $table->boolean('samples');
            // $table->string('delivery_plan', 150)->nullable();
            // $table->string('evaluation_committee_proposal', 200);
            // $table->text('payment_conditions');
            // $table->text('contract_guarantee');
            // $table->string('product_guarantee', 200);
            // $table->string('contract_administrator', 150);
            // $table->string('contract_validity', 200);
            // $table->string('additional_technical_documents', 200)->nullable();
            // $table->string('additional_qualified_documents', 200)->nullable();
            // $table->string('price_sheet', 150)->nullable();
            // $table->string('property_title', 200)->nullable();
            // $table->string('magnetic_medium', 50)->nullable();
            // $table->string('referring_person_data', 100)->nullable();
            // $table->string('form4_city', 100);
            // $table->date('form4_date');
            // $table->string('dncp_resolution_number', 8);
            // $table->date('dncp_resolution_date');
            // $table->enum('urgency_state', ['BAJA', 'MEDIA', 'ALTA'])->default('MEDIA');
            //                               // update and create timestamps columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
