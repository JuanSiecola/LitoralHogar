<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    protected function profileRules(?int $userId = null): array
    {
        return [
            'cedula' => ['nullable', 'numeric','digits:8',
                Rule::unique('perfil_persona', 'cedula')
                    ->when($userId !== null, fn($q) => $q->ignore($userId)),
            ],
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',
                $userId === null
                ? Rule::unique(User::class)
                : Rule::unique(User::class)->ignore($userId),
            ],
            'telefono' => ['required', 'string', 'regex:/^09\d{7}$/'],
            'foto_url' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function profileMessages(): array
    {
        return [
            'cedula.unique' => 'Esta cédula ya existe en el sistema.',
            'cedula.numeric' => 'La cédula solo admite números',
            'cedula.digits' => 'La cédula debe tener exactamente 8 dígitos, sin puntos ni guiones.',
            'email.unique' => 'Este correo electrónico ya ha sido registrado.',
            'email.required' => 'El correo electrónico es obligatorio',
            'telefono.regex' => 'El teléfono debe comenzar con 09 y tener 9 dígitos (ej: 099123456).',
            'foto_url.max' => 'La imagen no puede superar los 2MB.',
        ];
    }
}
