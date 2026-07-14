<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['id' => 1, 'name' => 'admin', 'description' => 'Administrador del Sistema']);
        Role::create(['id' => 2, 'name' => 'orders', 'description' => 'Pedidos']);
        Role::create(['id' => 3, 'name' => 'process_orders', 'description' => 'Procesar Pedidos']);
        Role::create(['id' => 4, 'name' => 'derive_orders', 'description' => 'Derivar Pedidos']);
        Role::create(['id' => 5, 'name' => 'plannings', 'description' => 'Planificación']);
        Role::create(['id' => 6, 'name' => 'minor_purchases', 'description' => 'Compras Menores']);
        Role::create(['id' => 7, 'name' => 'awards', 'description' => 'Adjudicaciones']);
        Role::create(['id' => 8, 'name' => 'contracts', 'description' => 'Contratos y Garantías']);
        Role::create(['id' => 9, 'name' => 'tenders', 'description' => 'Licitaciones']);
        Role::create(['id' => 10, 'name' => 'exceptions', 'description' => 'Excepciones']);

        // Seteamos el numero de sequencia igual al ultimo id
        DB::statement("SELECT setval('roles_id_seq', 10, true)");
    }
}
