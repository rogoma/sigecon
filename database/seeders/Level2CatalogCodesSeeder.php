<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level2CatalogCode;

class Level2CatalogCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Level2CatalogCode::create([
            "level1_catalog_code_id" => 1,
            "code" => "42130000",
            "description" => "Telas y vestidos medicos"
        ]);
        Level2CatalogCode::create([
            "level1_catalog_code_id" => 1,
            "code" => "42150000",
            "description" => "Equipos y suministros dentales"
        ]);
        Level2CatalogCode::create([
            "level1_catalog_code_id" => 1,
            "code" => "42290000",
            "description" => "Productos quirurgicos"
        ]);
    }
}
