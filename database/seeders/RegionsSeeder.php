<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::create([
            'codreg' => 1,
            'subcreg' => '.',
            'nomreg' => 'CONCEPCION'
        ]);
        Region::create([
            'codreg' => 3,
            'subcreg' => '.',
            'nomreg' => 'CORDILLERA'
        ]);
        Region::create([
            'codreg' => 4,
            'subcreg' => '.',
            'nomreg' => 'GUAIRA'
        ]);
        Region::create([
            'codreg' => 5,
            'subcreg' => '.',
            'nomreg' => 'CAAGUAZU'
        ]);
        Region::create([
            'codreg' => 6,
            'subcreg' => '.',
            'nomreg' => 'CAAZAPA'
        ]);
        Region::create([
            'codreg' => 7,
            'subcreg' => '.',
            'nomreg' => 'ITAPUA'
        ]);
        Region::create([
            'codreg' => 8,
            'subcreg' => '.',
            'nomreg' => 'MISIONES'
        ]);
        Region::create([
            'codreg' => 9,
            'subcreg' => '.',
            'nomreg' => 'PARAGUARI'
        ]);
        Region::create([
            'codreg' => 11,
            'subcreg' => '.',
            'nomreg' => 'CENTRAL'
        ]);
        Region::create([
            'codreg' => 12,
            'subcreg' => '.',
            'nomreg' => 'ÑEEMBUCU'
        ]);
        Region::create([
            'codreg' => 13,
            'subcreg' => '.',
            'nomreg' => 'AMAMBAY'
        ]);
        Region::create([
            'codreg' => 14,
            'subcreg' => '.',
            'nomreg' => 'CANINDEYU'
        ]);
        Region::create([
            'codreg' => 15,
            'subcreg' => '.',
            'nomreg' => 'PTE. HAYES'
        ]);
        Region::create([
            'codreg' => 10,
            'subcreg' => '.',
            'nomreg' => 'ALTO PARANA'
        ]);
        Region::create([
            'codreg' => 18,
            'subcreg' => '.',
            'nomreg' => 'CAPITAL'
        ]);
        Region::create([
            'codreg' => 50,
            'subcreg' => '.',
            'nomreg' => 'EXTRANJERO'
        ]);
        Region::create([
            'codreg' => 2,
            'subcreg' => 'SUR',
            'nomreg' =>'SAN PEDRO SUR'
        ]);
        Region::create([
            'codreg' => 2,
            'subcreg' => 'NOR',
            'nomreg' =>'SAN PEDRO NORTE'
        ]);
        Region::create([
            'codreg' => 17,
            'subcreg' => '.',
            'nomreg' => 'ALTO PARAGUAY'
        ]);
        Region::create([
            'codreg' => 16,
            'subcreg' => '.',
            'nomreg' => 'BOQUERON'
        ]);
    }
}
