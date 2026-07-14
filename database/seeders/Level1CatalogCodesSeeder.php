<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level1CatalogCode;

class Level1CatalogCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Level1CatalogCode::create([
            "code" => "42000000",
            "description" => "Equipos Accesorios y Suministros Medicos"
        ]);
    }
}
