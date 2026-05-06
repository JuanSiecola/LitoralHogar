<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use App\Models\PerfilPersona;
use App\Models\Inmobiliaria;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $rolInmobiliaria = Rol::where('nombre', 'inmobiliaria')->first();
        $rolAgente = Rol::where('nombre', 'agente')->first();
        $rolCliente = Rol::where('nombre', 'cliente')->first();
        $admin = User::where('email', 'admin@litoralhogar.com')->first();
        // --- Inmobiliarias ---
        $inmobiliarias = [
            [
                'email' => 'inmobiliaria1@litoralhogar.com',
                'empresa' => [
                    'razon_social' => 'Litoral Propiedades S.A.',
                    'rut' => '21500001001',
                    'direccion' => 'Av. 18 de Julio 1234, Montevideo',
                    'telefono' => '099111222',
                    'logo_url' => null,
                ],
            ],
            [
                'email' => 'inmobiliaria2@litoralhogar.com',
                'empresa' => [
                    'razon_social' => 'Río de la Plata Inmuebles',
                    'rut' => '21500002001',
                    'direccion' => 'Rambla Gran Bretaña 789, Montevideo',
                    'telefono' => '099333444',
                    'logo_url' => null,
                ],
            ],
        ];

        foreach ($inmobiliarias as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['password' => Hash::make('password123'), 'email_verified_at' => now()]
            );
            $user->rol_usuario()->syncWithoutDetaching([$rolInmobiliaria->id]);
            Inmobiliaria::firstOrCreate(
                ['usuario_id' => $user->id],
                array_merge($data['empresa'], ['usuario_id' => $user->id])
            );
        }

        // --- Agentes ---
        $agentes = [
            [
                'email' => 'agente1@litoralhogar.com',
                'perfil' => ['nombre' => 'Carlos', 'apellido' => 'Rodríguez', 'cedula' => '12345671', 'telefono' => '099555001'],
            ],
            [
                'email' => 'agente2@litoralhogar.com',
                'perfil' => ['nombre' => 'María', 'apellido' => 'González', 'cedula' => '23456782', 'telefono' => '099555002'],
            ],
            [
                'email' => 'agente3@litoralhogar.com',
                'perfil' => ['nombre' => 'Pablo', 'apellido' => 'Fernández', 'cedula' => '34567893', 'telefono' => '099555003'],
            ],
        ];

        foreach ($agentes as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['password' => Hash::make('password123'), 'email_verified_at' => now()]
            );
            $user->rol_usuario()->syncWithoutDetaching([$rolAgente->id]);
            PerfilPersona::firstOrCreate(
                ['usuario_id' => $user->id],
                array_merge($data['perfil'], ['usuario_id' => $user->id])
            );
        }

        // --- Clientes ---
        $clientes = [
            ['email' => 'cliente1@gmail.com', 'perfil' => ['nombre' => 'Laura', 'apellido' => 'Martínez', 'cedula' => '45678904', 'telefono' => '098100001']],
            ['email' => 'cliente2@gmail.com', 'perfil' => ['nombre' => 'Diego', 'apellido' => 'Pérez', 'cedula' => '56789015', 'telefono' => '098100002']],
            ['email' => 'cliente3@gmail.com', 'perfil' => ['nombre' => 'Valentina', 'apellido' => 'López', 'cedula' => '67890126', 'telefono' => '098100003']],
            ['email' => 'cliente4@gmail.com', 'perfil' => ['nombre' => 'Matías', 'apellido' => 'García', 'cedula' => '78901237', 'telefono' => '098100004']],
            ['email' => 'cliente5@gmail.com', 'perfil' => ['nombre' => 'Sofía', 'apellido' => 'Hernández', 'cedula' => '89012348', 'telefono' => '098100005']],
        ];

        foreach ($clientes as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['password' => Hash::make('password123'), 'email_verified_at' => now()]
            );
            $user->rol_usuario()->syncWithoutDetaching([$rolCliente->id]);
            PerfilPersona::firstOrCreate(
                ['usuario_id' => $user->id],
                array_merge($data['perfil'], ['usuario_id' => $user->id])
            );
        }

        // --- Admin ---
        $admin = User::where('email', 'admin@litoralhogar.com')->first();

        if (!$admin) {
            $user = User::firstOrCreate(
                ['email' => 'admin@litoralhogar.com'],
                [
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'is_admin' => true,
                ]
            );

            PerfilPersona::firstOrCreate(
                ['usuario_id' => $user->id],
                [
                    'usuario_id' => $user->id,
                    'nombre' => 'Administrador',
                    'apellido' => 'Sistema',
                    'cedula' => '00000001',
                    'telefono' => '0990000000',
                ]
            );
        }
    }
}
