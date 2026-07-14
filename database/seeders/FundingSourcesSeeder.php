<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FundingSource;

class FundingSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        FundingSource::create(['code' => '10', 'description' => 'RECURSOS DEL TESORO']);
        FundingSource::create(['code' => '20', 'description' => 'RECURSOS DEL CRÉDITO PÚBLICO']);
        FundingSource::create(['code' => '30', 'description' => 'RECURSOS INSTITUCIONALES']);
    }
}
