<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateViewOrdersItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement ("CREATE VIEW public.vista_orders_items  AS SELECT  vista_orders.order_id,
            vista_orders.dependency,
            items.batch,
            items.item_number,
            vista_orders.code_supexp,
            vista_orders.exp_obj_code,
            vista_orders.program_type_code,
            vista_orders.program_code,
            vista_orders.activity_code,
            vista_orders.ff_code,
            level5_catalog_codes.code,
            level5_catalog_codes.description AS level5_catalog_codes_description,
            items.technical_specifications,
            order_presentations.description AS order_presentations_description,
            order_measurement_units.description AS
            order_measurement_units_description,
            items.quantity,
            items.unit_price,
            items.total_amount
        FROM vista_orders
        JOIN items ON vista_orders.order_id = items.order_id
        JOIN level5_catalog_codes ON items.level5_catalog_code_id =
        level5_catalog_codes.id
        JOIN order_measurement_units ON items.order_measurement_unit_id =
        order_measurement_units.id
        JOIN order_presentations ON items.order_presentation_id =
        order_presentations.id
        ORDER BY vista_orders.order_id,
        items.item_number;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_orders_items');
    }
}
