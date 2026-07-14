<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => 1,
            'name' => 'Usuario',
            'lastname' => 'Administrador',
            'document' => '123456',
            'email' => '',
            'password' => Hash::make('123456'),
            'dependency_id' => '2',
            'position_id' => '3',
            'role_id' => 1
        ]);

        User::create([
            'id' => 2,
            'name' => 'Usuario',
            'lastname' => 'Pedidos',
            'document' => '222222',
            'email' => '',
            'password' => Hash::make('222222'),
            'dependency_id' => '117',
            'position_id' => '3',
            'role_id' => 2
        ]);

        User::create([
            'id' => 3,
            'name' => 'Usuario',
            'lastname' => 'DOC',
            'document' => '333333',
            'email' => '',
            'password' => Hash::make('333333'),
            'dependency_id' => '16',
            'position_id' => '2',
            'role_id' => 3
        ]);

        User::create([
            'id' => 4,
            'name' => 'Usuario',
            'lastname' => 'DGAF',
            'document' => '444444',
            'email' => '',
            'password' => Hash::make('444444'),
            'dependency_id' => '2',
            'position_id' => '1',
            'role_id' => 4
        ]);

        User::create([
            'id' => 5,
            'name' => 'Usuario',
            'lastname' => 'Planificacion',
            'document' => '555555',
            'email' => '',
            'password' => Hash::make('555555'),
            'dependency_id' => '59',
            'position_id' => '3',
            'role_id' => 5
        ]);

        User::create([
            'id' => 6,
            'name' => 'Usuario',
            'lastname' => 'UTA',
            'document' => '565656',
            'email' => '',
            'password' => Hash::make('565656'),
            'dependency_id' => '56',
            'position_id' => '3',
            'role_id' => 4
        ]);

        User::create([
            'id' => 7,
            'name' => 'Usuario',
            'lastname' => 'Asesoría Jurídica y Técnica',
            'document' => '575757',
            'email' => '',
            'password' => Hash::make('575757'),
            'dependency_id' => '57',
            'position_id' => '3',
            'role_id' => 4
        ]);

        User::create([
            'id' => 8,
            'name' => 'Usuario',
            'lastname' => 'Dpto. de Licitaciones',
            'document' => '626262',
            'email' => '',
            'password' => Hash::make('626262'),
            'dependency_id' => '62',
            'position_id' => '3',
            'role_id' => 9
        ]);

        User::create([
            'id' => 9,
            'name' => 'Usuario',
            'lastname' => 'Dpto. de Contratos y Garantías',
            'document' => '606060',
            'email' => '',
            'password' => Hash::make('606060'),
            'dependency_id' => '60',
            'position_id' => '3',
            'role_id' => 4
        ]);

        User::create([
            'id' => 10,
            'name' => 'Usuario',
            'lastname' => 'Dpto. de Compras Menores',
            'document' => '616161',
            'email' => '',
            'password' => Hash::make('616161'),
            'dependency_id' => '61',
            'position_id' => '3',
            'role_id' => 6 
        ]);

        User::create([
            'id' => 11,
            'name' => 'Usuario',
            'lastname' => 'Dpto. Procesos Complementarios y Excepciones',
            'document' => '636363',
            'email' => '',
            'password' => Hash::make('636363'),
            'dependency_id' => '63',
            'position_id' => '3',
            'role_id' => 4
        ]);

        User::create([
            'id' => 12,
            'name' => 'Usuario',
            'lastname' => 'Dpto. de Adjudicaciones',
            'document' => '646464',
            'email' => '',
            'password' => Hash::make('646464'),
            'dependency_id' => '64',
            'position_id' => '3',
            'role_id' => 4
        ]);

        User::create([
            'id' => 13,
            'name' => 'Usuario',
            'lastname' => 'Coordinación',
            'document' => '550055',
            'email' => '',
            'password' => Hash::make('550055'),
            'dependency_id' => '55',
            'position_id' => '3',
            'role_id' => 4
        ]);


        // Seteamos el numero de sequencia igual al ultimo id
        DB::statement("SELECT setval('users_id_seq', 13, true)");
    }
}
