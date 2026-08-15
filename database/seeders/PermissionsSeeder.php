<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [

            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',

            'ver pacientes',
            'crear pacientes',
            'editar pacientes',
            'eliminar pacientes',

            'ver citas',
            'crear citas',
            'editar citas',
            'eliminar citas',

            'ver consultas',
            'crear consultas',

            'ver antecedentes',
            'crear antecedentes',

            'ver estudios',
            'crear estudios',
            'descargar estudios',

            'crear diagnosticos',
            'crear tratamientos',
            'crear procedimientos',
            'crear signos vitales',
            'crear examen fisico',
            'crear evoluciones',
            'generar recetas',

            'ver caja',
            'abrir caja',
            'registrar pagos',
            'registrar egresos',
            'cerrar caja',
            'ver conciliacion caja',

        ];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);

        }

        $admin = Role::findByName('admin');

        $admin->syncPermissions(Permission::all());
    }
}
