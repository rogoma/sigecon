<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderPresentation;

class OrderPresentationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderPresentation::create([
            'description' => 'UNIDAD',
        ]);
        OrderPresentation::create([
            'description' => 'CAJA',
        ]);
        OrderPresentation::create([
            'description' => 'ENVASE',
        ]);
        OrderPresentation::create([
            'description' => 'BLISTER',
        ]);
    }
}
