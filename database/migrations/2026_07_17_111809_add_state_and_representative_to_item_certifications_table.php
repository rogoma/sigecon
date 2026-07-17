<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateAndRepresentativeToItemCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_certifications', function (Blueprint $table) {
            // 1 = Emitido (editable). Se reserva el rango para futuros estados (ej. Cerrado/Anulado).
            $table->smallInteger('state_id')->default(1)->after('creator_user_id');
            // Nombre del representante de la Contratista capturado al grabar el acta (se imprime en el PDF)
            $table->string('contratista_representative', 150)->nullable()->after('state_id');
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
            $table->dropColumn(['state_id', 'contratista_representative']);
        });
    }
}
