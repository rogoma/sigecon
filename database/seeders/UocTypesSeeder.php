<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UocType;

class UocTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UocType::create(['description' => 'UOC']);
        UocType::create(['description' => 'Sub-UOC']);
    }
}
