<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::create([
            'coddpto' => 1, 
            'nomdpto' => 'CONCEPCION'
        ]);
        Department::create([
            'coddpto' => 2, 
            'nomdpto' => 'SAN PEDRO'
        ]);
        Department::create([
            'coddpto' => 3, 
            'nomdpto' => 'CORDILLERA'
        ]);
        Department::create([
            'coddpto' => 4, 
            'nomdpto' => 'GUAIRA'
        ]);
        Department::create([
            'coddpto' => 5, 
            'nomdpto' => 'CAAGUAZU'
        ]);
        Department::create([
            'coddpto' => 6, 
            'nomdpto' => 'CAAZAPA'
        ]);
        Department::create([
            'coddpto' => 7, 
            'nomdpto' => 'ITAPUA'
        ]);
        Department::create([
            'coddpto' => 8, 
            'nomdpto' => 'MISIONES'
        ]);
        Department::create([
            'coddpto' => 9, 
            'nomdpto' => 'PARAGUARI'
        ]);
        Department::create([
            'coddpto' => 10, 
            'nomdpto' => 'ALTO PARANA'
        ]);
        Department::create([
            'coddpto' => 11, 
            'nomdpto' => 'CENTRAL'
        ]);
        Department::create([
            'coddpto' => 12, 
            'nomdpto' => 'ÑEEMBUCU'
        ]);
        Department::create([
            'coddpto' => 13, 
            'nomdpto' => 'AMAMBAY'
        ]);
        Department::create([
            'coddpto' => 14, 
            'nomdpto' => 'CANINDEYU'
        ]);
        Department::create([
            'coddpto' => 15, 
            'nomdpto' => 'PTE. HAYES'
        ]);
        Department::create([
            'coddpto' => 18, 
            'nomdpto' => 'CAPITAL'
        ]);
        Department::create([
            'coddpto' => 50, 
            'nomdpto' => 'EXTRANJERO'
        ]);
        Department::create([
            'coddpto' => 17, 
            'nomdpto' => 'ALTO PARAGUAY'
        ]);
        Department::create([
            'coddpto' => 16, 
            'nomdpto' => 'BOQUERON'
        ]);
    }
}
