<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id')->autoIncrement(); // set auto-increment and primary key
            
            $table->integer('number')->nullable();
            $table->integer('dependency_id');
            $table->string('responsible', 100);
            $table->smallInteger('modality_id');
            $table->integer('dncp_pac_id');
            $table->date('begin_date')->nullable(); // se admite null
            $table->smallInteger('sub_program_id');
            $table->smallInteger('funding_source_id');
            $table->smallInteger('financial_organism_id');
            $table->bigInteger('total_amount');

            $table->string('description',200);
            $table->boolean('ad_referendum');
            $table->boolean('plurianualidad');
            $table->enum('system_awarded_by', ['LOTE', 'ÍTEM', 'TOTAL', 'COMBINADO']);
            $table->smallInteger('expenditure_object_id');
            $table->boolean('fonacide');
            $table->boolean('catalogs_technical_annexes');
            $table->boolean('alternative_offers');
            $table->boolean('open_contract');
            $table->string('period_time', 50);
            $table->boolean('manufacturer_authorization');
            $table->boolean('financial_advance_percentage_amount');
            $table->string('technical_specifications', 100);
            $table->boolean('samples');
            $table->string('delivery_plan', 150)->nullable();
            $table->string('evaluation_committee_proposal', 200);
            $table->text('payment_conditions');
            $table->text('contract_guarantee');
            $table->string('product_guarantee', 200);
            $table->string('contract_administrator', 150);
            $table->string('contract_validity', 200);
            $table->string('additional_technical_documents', 200)->nullable();
            $table->string('additional_qualified_documents', 200)->nullable();
            $table->string('price_sheet', 150)->nullable();
            $table->string('property_title', 200)->nullable();
            $table->string('magnetic_medium', 50)->nullable();
            $table->string('referring_person_data', 100)->nullable();

            $table->string('form4_city', 100);
            $table->date('form4_date');
            $table->string('dncp_resolution_number', 8);
            $table->date('dncp_resolution_date');

            $table->enum('urgency_state', ['BAJA', 'MEDIA', 'ALTA'])->default('MEDIA');
            $table->smallInteger('actual_state');   // Estado actual
            $table->integer('creator_user_id');
            $table->integer('modifier_user_id')->nullable();
            $table->timestamps();                               // update and create timestamps columns
            // INDEXES
            $table->foreign('dependency_id')->references('id')->on('dependencies')->onUpdate('cascade');
            $table->foreign('modality_id')->references('id')->on('modalities')->onUpdate('cascade');
            $table->foreign('sub_program_id')->references('id')->on('sub_programs')->onUpdate('cascade');
            $table->foreign('funding_source_id')->references('id')->on('funding_sources')->onUpdate('cascade');
            $table->foreign('financial_organism_id')->references('id')->on('financial_organisms')->onUpdate('cascade');
            $table->foreign('expenditure_object_id')->references('id')->on('expenditure_objects')->onUpdate('cascade');
            $table->foreign('creator_user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('modifier_user_id')->references('id')->on('users')->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
