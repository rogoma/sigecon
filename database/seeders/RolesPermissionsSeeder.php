<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Rol Administrador del Sistema (modulo admin)
        $permisos = DB::select("SELECT id FROM permissions WHERE description LIKE 'admin.%'");
        foreach ($permisos as $row) {
            RolePermission::create(['role_id' => 1, 'permission_id' => $row->id]);
        }

        // Rol Pedidos (modulo orders)
        $permisos = DB::select("SELECT id FROM permissions WHERE description LIKE 'orders.%'");
        foreach ($permisos as $row) {
            RolePermission::create(['role_id' => 2, 'permission_id' => $row->id]);
        }

        // Rol Procesar Pedidos (DOC) (Modulo process_orders)
        $permisos = DB::select("SELECT id FROM permissions WHERE description LIKE 'process_orders.%'");
        foreach ($permisos as $row) {
            RolePermission::create(['role_id' => 3, 'permission_id' => $row->id]);
        }

        // Rol Derivar Pedidos (DGAF) (Modulo derive_orders)
        $permisos = DB::select("SELECT id FROM permissions WHERE description LIKE 'derive_orders.%'");
        foreach ($permisos as $row) {
            RolePermission::create(['role_id' => 4, 'permission_id' => $row->id]);
        }

        // Rol Planificación (Modulo plannings)
        $permisos = DB::select("SELECT id FROM permissions WHERE description LIKE 'plannings.%'");
        foreach ($permisos as $row) {
            RolePermission::create(['role_id' => 5, 'permission_id' => $row->id]);
        }

        // Rol Commpras menores (Modulo minor_purchases)
        $permisos = DB::select("SELECT id FROM permissions WHERE description LIKE 'minor_purchases.%'");
        foreach ($permisos as $row) {
            RolePermission::create(['role_id' => 6, 'permission_id' => $row->id]);
        }

        // Rol Adjudicaciones (Modulo awards)
        $permisos = DB::select("SELECT id FROM permissions WHERE description LIKE 'awards.%'");
        foreach ($permisos as $row) {
            RolePermission::create(['role_id' => 7, 'permission_id' => $row->id]);
        }

        // Rol Contratos (Modulo contracts)
        $permisos = DB::select("SELECT id FROM permissions WHERE description LIKE 'contracts.%'");
        foreach ($permisos as $row) {
            RolePermission::create(['role_id' => 8, 'permission_id' => $row->id]);
        }

        // Rol Licitaciones (Modulo tenders)
        $permisos = DB::select("SELECT id FROM permissions WHERE description LIKE 'tenders.%'");
        foreach ($permisos as $row) {
            RolePermission::create(['role_id' => 9, 'permission_id' => $row->id]);
        }

        // Rol Pro. Complem. y Excepiones (Modulo exceptions)
        $permisos = DB::select("SELECT id FROM permissions WHERE description LIKE 'exceptions.%'");
        foreach ($permisos as $row) {
            RolePermission::create(['role_id' => 10, 'permission_id' => $row->id]);
        }    



    }
}
