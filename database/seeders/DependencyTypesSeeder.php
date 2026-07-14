<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DependencyType;

class DependencyTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DependencyType::create(['description' => 'Ministro']);
        DependencyType::create(['description' => 'Viceministerio']);
        DependencyType::create(['description' => 'Dirección General']);
        DependencyType::create(['description' => 'Dirección de Nivel']);
        DependencyType::create(['description' => 'Departamento']);
        DependencyType::create(['description' => 'Sección']);
        DependencyType::create(['description' => 'Coordinación']);
        DependencyType::create(['description' => 'Asesoría']);
        DependencyType::create(['description' => 'Unidad']);
        DependencyType::create(['description' => 'Sub Unidad']);
        DependencyType::create(['description' => 'UOC']);
        DependencyType::create(['description' => 'Sub-UOC']);
        DependencyType::create(['description' => 'Secretaría']);
        DependencyType::create(['description' => 'Gestión Documental']);
        DependencyType::create(['description' => 'Almacén del Nivel Central']);
        DependencyType::create(['description' => 'Ventanilla Única de Proveedores RSG N° 382/14']);
        DependencyType::create(['description' => 'UEP']);   
    }
}