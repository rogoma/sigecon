<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramType;

class ProgramTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProgramType::create([
            'description' => 'PRESUPUESTO DE PROGRAMAS CENTRALES',
            'code' => 1,
        ]);
        ProgramType::create([
            'description' => 'PRESUPUESTO DE PROGRAMAS SUSTANTIVOS',
            'code' => 2,
        ]);
        ProgramType::create([
            'description' => 'PRESUPUESTO DE PARTIDAS NO ASIGNABLES A PROGRAMAS',
            'code' => 3,
        ]);
    }
}
