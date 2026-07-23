<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiscalizacionRepresentativeToItemCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_certifications', function (Blueprint $table) {
            // Nombre del representante de la Fiscalización capturado al grabar el acta (se imprime en el PDF)
            $table->string('fiscalizacion_representative', 150)->nullable()->after('contratista_representative');
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
            $table->dropColumn('fiscalizacion_representative');
        });
    }
}
