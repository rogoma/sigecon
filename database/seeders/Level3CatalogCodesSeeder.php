<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level3CatalogCode;

class Level3CatalogCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Level3CatalogCode::create([
            "level2_catalog_code_id" => 1,
            "code" => "42131600",
            "description" => "Vestuario para el personal sanitario y articulos relacionados",
        ]);
        Level3CatalogCode::create([
            "level2_catalog_code_id" => 1,
            "code" => "42132200",
            "description" => "Guantes y accesorios medicos",
        ]);
        Level3CatalogCode::create([
            "level2_catalog_code_id" => 1,
            "code" => "42131700",
            "description" => "Prendas textiles quirurgicas",
        ]);

        Level3CatalogCode::create([
            "level2_catalog_code_id" => 2,
            "code" => "42152200",
            "description" => "Equipo y suministros dentales de laboratorio y de esterilizacion",
        ]);
        Level3CatalogCode::create([
            "level2_catalog_code_id" => 2,
            "code" => "42151700",
            "description" => "Muebles para la clinica dental",
        ]);

        Level3CatalogCode::create([
            "level2_catalog_code_id" => 3,
            "code" => "42291700",
            "description" => "Taladros quirurgicos de mano y escariadores y instrumentos de punzonar y accesorios y productos relacionados",
        ]);
        Level3CatalogCode::create([
            "level2_catalog_code_id" => 3,
            "code" => "42294200",
            "description" => "Juegos de instrumentos y sistemas y bandejas quirurgicos",
        ]);
    }
}
