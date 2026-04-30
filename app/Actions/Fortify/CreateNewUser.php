<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $rules = [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'roles' => ['required', 'integer', 'exists:roles,id'],
        ];

        // Si selecciona inmobiliaria, validar campos adicionales
        if ($this->isInmobiliariaRole($input['roles'] ?? null)) {
            $rules = array_merge($rules, [
                'razon_social' => ['required', 'string', 'max:255'],
                'rut' => ['required', 'string', 'unique:detalle_inmobiliaria,rut'],
                'direccion' => ['required', 'string', 'max:255'],
                'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
        }

        Validator::make($input, $rules, $this->profileMessages())->validate();

        $user = User::create([
            'cedula' => $input['cedula'],
            'nombre' => $input['nombre'],
            'apellido' => $input['apellido'],
            'email' => $input['email'],
            'telefono' => $input['telefono'],
            'password' => $input['password'],
        ]);

        // Asignar rol
        $user->rol_usuario()->attach($input['roles']);

        // Si es inmobiliaria, crear registro en detalle_inmobiliaria
        if ($this->isInmobiliariaRole($input['roles'])) {
            $logoUrl = null;

            // Procesar imagen si existe
            if (!empty($input['logo']) && $input['logo'] instanceof \Illuminate\Http\UploadedFile) {
                $logoUrl = $input['logo']->store('inmobiliarias/logos', 'public');
            }

            $user->detalle_inmobiliaria()->create([
                'razon_social' => $input['razon_social'],
                'rut' => $input['rut'],
                'direccion' => $input['direccion'],
                'logo_url' => $logoUrl,
            ]);
        }

        return $user;
    }

    /**
     * Verificar si el rol es inmobiliaria
     */
    private function isInmobiliariaRole($roleId): bool
    {
        if (!$roleId) {
            return false;
        }

        $inmobiliariaRole = Rol::where('nombre', 'inmobiliaria')
            ->where('activo', true)
            ->first();

        return $inmobiliariaRole && $inmobiliariaRole->id == $roleId;
    }
}