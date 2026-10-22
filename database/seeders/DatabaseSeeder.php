<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Poblar la base de datos de SisCoPaM con datos de prueba iniciales.
     */
    public function run(): void
    {
        // 1. Crear Roles del Sistema
        $roleSuperAdmin = Role::create([
            'nombre' => 'SUPER_ADMIN',
            'descripcion' => 'Administrador Global de la Plataforma SaaS',
            'permisos_json' => ['all' => true],
        ]);

        $roleAdminGym = Role::create([
            'nombre' => 'ADMIN_GYM',
            'descripcion' => 'Dueño / Gerente del Gimnasio',
            'permisos_json' => [
                'gestionar_sedes' => true,
                'gestionar_usuarios' => true,
                'gestionar_planes' => true,
                'ver_reportes' => true,
                'gestionar_inventario' => true,
            ],
        ]);

        $roleRecepcion = Role::create([
            'nombre' => 'RECEPCIONISTA',
            'descripcion' => 'Personal de Recepción y Control de Acceso',
            'permisos_json' => [
                'registrar_pagos' => true,
                'realizar_checkin' => true,
                'ver_clientes' => true,
            ],
        ]);

        $roleEntrenador = Role::create([
            'nombre' => 'ENTRENADOR',
            'descripcion' => 'Entrenador de Gimnasio / Evaluador Físico',
            'permisos_json' => [
                'evaluaciones_fisicas' => true,
                'ver_clientes' => true,
            ],
        ]);

        // 2. Crear Empresa Demo (Tenant)
        $empresaDemo = Empresa::create([
            'nombre' => 'FitGym Center C.A.',
            'rif' => 'J-50012345-6',
            'plan' => 'basico',
            'configuracion' => [
                'moneda_principal' => 'USD',
                'permitir_pago_movil' => true,
                'notificaciones_whatsapp' => true,
            ],
        ]);

        // 3. Crear Sede Principal de la Empresa
        $sedePrincipal = Sede::create([
            'empresa_id' => $empresaDemo->id,
            'nombre' => 'Sede Principal - Centro',
            'direccion' => 'Av. San Martín, Turén, Portuguesa',
            'horarios_json' => [
                'lunes_viernes' => ['apertura' => '06:00', 'cierre' => '21:00'],
                'sabados' => ['apertura' => '08:00', 'cierre' => '16:00'],
                'domingos' => null,
            ],
        ]);

        // 4. Crear Usuario SuperAdmin (Propietario del SaaS)
        User::create([
            'name' => 'Super Admin SisCoPaM',
            'email' => 'admin@siscopam.com',
            'cedula' => 'V-00000000',
            'telefono' => '+584120000000',
            'password' => Hash::make('superpassword123'),
            'role_id' => $roleSuperAdmin->id,
            'activo' => true,
        ]);

        // 5. Crear Usuario Administrador del Gimnasio Demo
        User::create([
            'empresa_id' => $empresaDemo->id,
            'sede_id' => $sedePrincipal->id,
            'role_id' => $roleAdminGym->id,
            'name' => 'Gerente FitGym',
            'email' => 'gerencia@fitgym.com',
            'cedula' => 'V-18234567',
            'telefono' => '+584141234567',
            'password' => Hash::make('fitgym2026'),
            'activo' => true,
        ]);

        // 6. Crear Usuario Administrador del Gimnasio Demo
        User::create([
            'empresa_id' => $empresaDemo->id,
            'sede_id' => $sedePrincipal->id,
            'role_id' => $roleAdminGym->id,
            'name' => 'Gerente AntiFlatGym',
            'email' => 'gerencia@antiflatgym.com',
            'cedula' => 'V-18254987',
            'telefono' => '+584141298767',
            'password' => Hash::make('fitgym2026'),
            'activo' => true,
        ]);


        // 7. Crear Plan de Membresía Demo
        Plan::create([
            'empresa_id' => $empresaDemo->id,
            'nombre' => 'Membresía Mensual VIP',
            'precio_usd' => 30.00,
            'duracion_dias' => 30,
            'activo' => true,
        ]);
    }
}