<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level5CatalogCode;

class Level5CatalogCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Level5CatalogCode::create([
            "level4_catalog_code_id" => 1,
            "code" => "42131608-9995",
            "description" => "Pantalones exfoliantes - Mediano",
        ]);
        Level5CatalogCode::create([
            "level4_catalog_code_id" => 1,
            "code" => "42131608-9994",
            "description" => "Pantalones exfoliantes - Extragrande",
        ]);


        Level5CatalogCode::create([
            "level4_catalog_code_id" => 2,
            "code" => "42131609-9999",
            "description" => "Cubre calzados",
        ]);


        Level5CatalogCode::create([
            "level4_catalog_code_id" => 3,
            "code" => "42132203-9995",
            "description" => "Guante de nitrilo mediano",
        ]);
        Level5CatalogCode::create([
            "level4_catalog_code_id" => 3,
            "code" => "42132203-9998",
            "description" => "Guante de reconocimiento mediano",
        ]);

        Level5CatalogCode::create([
            "level4_catalog_code_id" => 4,
            "code" => "42132205-9999",
            "description" => "Guante quirúrgico de látex",
        ]);

        Level5CatalogCode::create([
            "level4_catalog_code_id" => 5,
            "code" => "42131702-9999",
            "description" => "Bata quirúrgica mediana",
        ]);
        Level5CatalogCode::create([
            "level4_catalog_code_id" => 5,
            "code" => "42131702-9997",
            "description" => "Bata quirúrgica extragrande",
        ]);

        Level5CatalogCode::create([
            "level4_catalog_code_id" => 6,
            "code" => "42131707-9999",
            "description" => "Mascarilla quirúrgica",
        ]);

        Level5CatalogCode::create([
            "level4_catalog_code_id" => 7,
            "code" => "42152211-9996",
            "description" => "Micromotor Odontologico",
        ]);

        Level5CatalogCode::create([
            "level4_catalog_code_id" => 8,
            "code" => "42151701-9969",
            "description" => "Suctor de Aerosoles Extraoral",
        ]);

        Level5CatalogCode::create([
            "level4_catalog_code_id" => 9,
            "code" => "42291707-9999",
            "description" => "Kits de Instumentos para Craneotomia",
        ]);

        Level5CatalogCode::create([
            "level4_catalog_code_id" => 10,
            "code" => "42294201-9999",
            "description" => "Kit de Insercion Percutanea",
        ]);

    }
}
