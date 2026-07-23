<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchUuidToItemCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_certifications', function (Blueprint $table) {
            // Identifica las Actas generadas juntas en una misma "tanda" (medición combinada de varias
            // Órdenes de Ejecución de una misma Localidad/Sub-Componente). Null para Actas de una sola Orden.
            $table->uuid('batch_uuid')->nullable()->after('order_id');
            $table->index('batch_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_certifications', function (Blueprint $table) {
            $table->dropColumn('batch_uuid');
        });
    }
}
