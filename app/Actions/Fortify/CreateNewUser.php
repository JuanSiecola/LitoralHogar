<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Inmobiliaria;
use App\Models\PerfilPersona;
use App\Concerns\InmobiliariaValidationRules;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\DB;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules, InmobiliariaValidationRules;

    public function create(array $input): User
    {
        $esInmobiliaria = $input['tipo'] === 'inmobiliaria';

        $rules = $this->profileRules();
        $rules['tipo'] = ['required', 'in:inmobiliaria,agente,cliente'];
        $rules['password'] = $this->passwordRules();

        if ($esInmobiliaria) {
            $rules['nombre'] = ['nullable'];
            $rules['apellido'] = ['nullable'];
        }

        $messages = array_merge($this->profileMessages(), $this->passwordMessages());

        if ($esInmobiliaria) {
            $rules    = array_merge($rules, $this->inmobiliariaRules());
            $messages = array_merge($messages, $this->inmobiliariaMessages());
        }

        Validator::make($input, $rules, $messages)->validate();

        return DB::transaction(function () use ($input, $esInmobiliaria) {
            $user = User::create([
                'email'    => $input['email'],
                'password' => $input['password'],
            ]);

            if ($esInmobiliaria) {
                Inmobiliaria::create([
                    'usuario_id'   => $user->id,
                    'razon_social' => $input['razon_social'],
                    'rut'          => $input['rut'],
                    'direccion'    => $input['direccion'],
                    'telefono'     => $input['telefono'],
                    'logo_url'     => null,
                ]);
            } else {
                PerfilPersona::create([
                    'usuario_id' => $user->id,
                    'nombre'     => $input['nombre'],
                    'apellido'   => $input['apellido'],
                    'cedula'     => $input['cedula'] ?? null,
                    'telefono'   => $input['telefono'],
                ]);
            }

            $rolNombre = match($input['tipo']) {
                'inmobiliaria' => 'inmobiliaria',
                'agente'       => 'agente',
                'cliente'      => 'cliente',
            };

            $roles = [Rol::where('nombre', $rolNombre)->value('id')];

            // Cliente que también es agente
            if ($input['tipo'] === 'cliente' && !empty($input['es_agente'])) {
                $roles[] = Rol::where('nombre', 'agente')->value('id');
            }

            $user->rol_usuario()->attach($roles, ['fecha_asignacion' => now()]);

            return $user;
        });
    }
}