<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::create(['description' => 'Dirección General']);
        Position::create(['description' => 'Dirección']);
        Position::create(['description' => 'Jefe de Departamento']);
        Position::create(['description' => 'Jefe de Unidad']);
    }
}
