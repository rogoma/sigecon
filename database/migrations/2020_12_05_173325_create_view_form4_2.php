<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateViewForm42 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW public.vista_form4_2 AS SELECT budget_request_providers.order_id,
            items.item_number,
            items.batch,
            item_award_histories.item_id,
            providers.description AS providers_description,
            level5_catalog_codes.description AS level5_catalog_codes_description,
            item_award_histories.dncp_pac_id,
            item_award_histories.amount
        FROM budget_request_providers
        RIGHT JOIN item_award_histories ON budget_request_providers.id =
        item_award_histories.budget_request_provider_id
        LEFT JOIN providers ON budget_request_providers.provider_id =
        providers.id
        JOIN items ON item_award_histories.item_id = items.id
        JOIN level5_catalog_codes ON items.level5_catalog_code_id =
        level5_catalog_codes.id
        ORDER BY item_award_histories.item_id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_form4_2');
    }
}
