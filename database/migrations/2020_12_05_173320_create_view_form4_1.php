<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateViewForm41 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW public.vista_form4_1 AS SELECT orders.id AS order_id,
            orders.form4_city,
            orders.form4_date,
            providers.description AS providers_description,
            providers.ruc
        FROM orders
        JOIN budget_request_providers ON orders.id =
        budget_request_providers.order_id
        JOIN providers ON budget_request_providers.provider_id = providers.id
        ORDER BY orders.id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_form4_1');
    }
}
