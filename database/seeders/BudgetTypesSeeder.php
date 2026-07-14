<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BudgetType;

class BudgetTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BudgetType::create([
            'description' => 'TIPO 1 - PRESUPUESTOS DE PROGRAMAS DE ADMINISTRACIÓN',
        ]);
        BudgetType::create([
            'description' => 'TIPO 2 - PRESUPUESTOS DE PROGRAMAS DE ACCIÓN',
        ]);
        BudgetType::create([
            'description' => 'TIPO 3 - PRESUPUESTOS DE PROGRAMAS DE INVERSIÓN',
        ]);
        BudgetType::create([
            'description' => 'TIPO 4 - PRESUPUESTOS DE PROGRAMAS DEL SERVICIO DE LA DEUDA PÚBLICA',
        ]);
    }
}
