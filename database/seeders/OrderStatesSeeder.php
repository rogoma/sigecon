<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Al crear un pedido ingresa con el estado SOLICITUD
        DB::table('order_states')->insert([ 'id' => 1, 'description' => 'SOLICITUD' ]);
        DB::table('order_states')->insert([ 'id' => 2, 'description' => 'PROCESADO PEDIDO' ]);
        // Al ser procesado por la DGAF cambia al estado PROCESADO DGAF
        DB::table('order_states')->insert([ 'id' => 5, 'description' => 'PROCESADO DGAF - ACEPTADO' ]);
        DB::table('order_states')->insert([ 'id' => 10, 'description' => 'PROCESADO DGAF - RECHAZADO' ]);
        // Al pasar al departamento de planificación cambia al estado PLANIFICACIÓN
        DB::table('order_states')->insert([ 'id' => 15, 'description' => 'RECIBIDO PLANIFICACIÓN' ]);
        DB::table('order_states')->insert([ 'id' => 20, 'description' => 'PROCESADO PLANIFICACIÓN' ]);
        // Al pasar al departamento de licitaciones cambia al estado LICITACIONES
        DB::table('order_states')->insert([ 'id' => 25, 'description' => 'RECIBIDO LICITACIONES' ]);
        DB::table('order_states')->insert([ 'id' => 30, 'description' => 'PROCESADO LICITACIONES' ]);
        // Al pasar al comite evaluador cambia al estado COMITE EVALUADOR
        DB::table('order_states')->insert([ 'id' => 35, 'description' => 'RECIBIDO COMITE EVALUADOR' ]);
        DB::table('order_states')->insert([ 'id' => 40, 'description' => 'PROCESADO COMITE EVALUADOR' ]);
        // Al pasar al departamento de compras menores cambia al estado COMPRAS MENORES
        DB::table('order_states')->insert([ 'id' => 45, 'description' => 'RECIBIDO COMPRAS MENORES' ]);
        DB::table('order_states')->insert([ 'id' => 50, 'description' => 'PROCESADO COMPRAS MENORES' ]);
        // Al pasar al departamento de proc. compl. y excepciones cambia al estado EXCEPCIONES
        DB::table('order_states')->insert([ 'id' => 55, 'description' => 'RECIBIDO EXCEPCIONES' ]);
        DB::table('order_states')->insert([ 'id' => 60, 'description' => 'PROCESADO EXCEPCIONES' ]);
        // Al pasar al departamento de adjudicaciones 1ra etapa cambia al estado ADJUDICACIONES - 1RA ETAPA
        DB::table('order_states')->insert([ 'id' => 65, 'description' => 'RECIBIDO ADJUDICACIONES - 1RA ETAPA' ]);
        DB::table('order_states')->insert([ 'id' => 70, 'description' => 'PROCESADO ADJUDICACIONES - 1RA ETAPA' ]);
        // Al pasar al departamento de contratos cambia al estado CONTRATOS
        DB::table('order_states')->insert([ 'id' => 75, 'description' => 'RECIBIDO CONTRATOS' ]);
        DB::table('order_states')->insert([ 'id' => 80, 'description' => 'PROCESADO CONTRATOS' ]);
        // Al pasar al departamento de adjudicaciones 2da etapa cambia al estado ADJUDICACIONES
        DB::table('order_states')->insert([ 'id' => 85, 'description' => 'RECIBIDO ADJUDICACIONES' ]);
        DB::table('order_states')->insert([ 'id' => 90, 'description' => 'PROCESADO ADJUDICACIONES' ]);
    }
}
