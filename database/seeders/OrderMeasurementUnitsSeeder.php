<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderMeasurementUnit;

class OrderMeasurementUnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderMeasurementUnit::create([
            'description' => 'UNIDAD',
        ]);
        OrderMeasurementUnit::create([
            'description' => 'CAJA',
        ]);
        OrderMeasurementUnit::create([
            'description' => 'EVENTO',
        ]);
    }
}
