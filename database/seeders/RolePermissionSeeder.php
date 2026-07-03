<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [
            'ver proyecto', 'crear proyecto', 'editar proyecto', 'eliminar proyecto',
            'gestionar miembros',
            'crear tarea', 'editar tarea', 'eliminar tarea', 'asignar tarea',
            'comentar',
            'gestionar usuarios',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        $lider = Role::firstOrCreate(['name' => 'lider']);
        $lider->syncPermissions([
            'ver proyecto', 'crear proyecto', 'editar proyecto', 'eliminar proyecto',
            'gestionar miembros',
            'crear tarea', 'editar tarea', 'eliminar tarea', 'asignar tarea',
            'comentar',
        ]);

        $colaborador = Role::firstOrCreate(['name' => 'colaborador']);
        $colaborador->syncPermissions([
            'ver proyecto',
            'crear tarea', 'editar tarea',
            'comentar',
        ]);

        $invitado = Role::firstOrCreate(['name' => 'invitado']);
        $invitado->syncPermissions([
            'ver proyecto',
            'comentar',
        ]);
    }
}
