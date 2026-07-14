<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Program::create([
            'description' => 'PROGRAMAS CENTRALES',
            'program_type_id' => 1,
            'code' => 1,
        ]);
        Program::create([
            'description' => 'ACCESO A LA ATENCIÓN PRIMARIA DE LA SALUD',
            'program_type_id' => 2,
            'code' => 1,
        ]);
        Program::create([
            'description' => 'SERVICIOS HOSPITALARIOS PARA LA REDUCCIÓN DE LA MORBIMORTALIDAD',
            'program_type_id' => 2,
            'code' => 2,
        ]);
        Program::create([
            'description' => 'SERVICIOS DE APOYO Y DIAGNÓSTICO PARA LA MEJORA EN LA ATENCIÓN',
            'program_type_id' => 2,
            'code' => 3,
        ]);
        Program::create([
            'description' => 'AUMENTO DE LA COBERTURA EN LA ATENCIÓN A ENFERMEDADES ESPECIALES',
            'program_type_id' => 2,
            'code' => 4,
        ]);
        Program::create([
            'description' => 'SEGURIDAD ALIMENTARIA NUTRICIONAL HUMANA MEJORADA',
            'program_type_id' => 2,
            'code' => 5,
        ]);
        Program::create([
            'description' => 'MEJORA EN EL BIENESTAR SOCIAL PARA PERSONAS EN SITUACIÓN DE RIESGO',
            'program_type_id' => 2,
            'code' => 6,
        ]);
        Program::create([
            'description' => 'ACCESO A LOS SERVICIOS DE AGUA POTABLE Y SANEAMIENTO AMBIENTAL',
            'program_type_id' => 2,
            'code' => 7,
        ]);
        Program::create([
            'description' => 'EMERGENCIA SANITARIA ANTE PANDEMIA COVID 19',
            'program_type_id' => 2,
            'code' => 8,
        ]);
        Program::create([
            'description' => 'PARTIDAS NO ASIGNABLES A PROGRAMAS',
            'program_type_id' => 3,
            'code' => 1,
        ]);
    }
}
