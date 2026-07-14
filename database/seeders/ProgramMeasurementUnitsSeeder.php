<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramMeasurementUnit;

class ProgramMeasurementUnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProgramMeasurementUnit::create([
            'description' => 'ACCIONES'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'ADM'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'ASISTENCIAS'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'ATENCIONES'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'CAPACITACIONES'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'CURSOS'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'DETERMINACIONES'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'DOSIS ENTREGADAS'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'EQUIPAM.'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'ESTABLECIMIENTOS'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'HABILITACIONES'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'INFORMES'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'INSUMOS'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'INTERVENCIONES'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'MEDICAMENTOS'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'OBRAS'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'REGISTROS'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'SERVICIOS'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'SISTEMAS'
        ]);
        ProgramMeasurementUnit::create([
            'description' => 'TRANSFERENCIAS'
        ]);
    }
}
