<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinancialLevel;

class FinancialLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FinancialLevel::create(['number' => 20, 'description' => '20']);
        FinancialLevel::create(['number' => 34, 'description' => '34']);
        FinancialLevel::create(['number' => 50, 'description' => '50']);
        FinancialLevel::create(['number' => 61, 'description' => '61']);
        FinancialLevel::create(['number' => 62, 'description' => '62']);
        FinancialLevel::create(['number' => 63, 'description' => '63']);
        FinancialLevel::create(['number' => 70, 'description' => '70']);
    }
}
