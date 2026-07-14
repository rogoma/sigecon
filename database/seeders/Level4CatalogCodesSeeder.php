<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level4CatalogCode;

class Level4CatalogCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Level4CatalogCode::create([
            "level3_catalog_code_id" => 1,
            "code" => "42131608",
            "description" => "Uniformes para el personal sanitario",
        ]);
        Level4CatalogCode::create([
            "level3_catalog_code_id" => 1,
            "code" => "42131609",
            "description" => "Cubrezapatos para el personal sanitario",
        ]);

        Level4CatalogCode::create([
            "level3_catalog_code_id" => 2,
            "code" => "42132203",
            "description" => "Guantes medicos de examen o para usos no quirurgicos",
        ]);
        Level4CatalogCode::create([
            "level3_catalog_code_id" => 2,
            "code" => "42132205",
            "description" => "Guantes quirurgicos",
        ]);

        Level4CatalogCode::create([
            "level3_catalog_code_id" => 3,
            "code" => "42131702",
            "description" => "Batas quirurgicas",
        ]);
        Level4CatalogCode::create([
            "level3_catalog_code_id" => 3,
            "code" => "42131707",
            "description" => "Trajes, cascos o mascarillas de aislamiento quirurgico o accesorios",
        ]);

        Level4CatalogCode::create([
            "level3_catalog_code_id" => 4,
            "code" => "42152211",
            "description" => "Tornos de laboratorio dental o accesorios",
        ]);
        Level4CatalogCode::create([
            "level3_catalog_code_id" => 5,
            "code" => "42151701",
            "description" => "Sillas de examen dental o piezas o accesorios relacionados",
        ]);

        Level4CatalogCode::create([
            "level3_catalog_code_id" => 6,
            "code" => "42291707",
            "description" => "Kits de craneotomia",
        ]);

        Level4CatalogCode::create([
            "level3_catalog_code_id" => 7,
            "code" => "42294201",
            "description" => "Juegos de instrumentos quirurgicos toracicos y cardiovasculares",
        ]);
    }
}
