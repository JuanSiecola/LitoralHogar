<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait InmobiliariaValidationRules
{
    protected function inmobiliariaRules(?int $inmobiliariaId = null): array
    {
        return [
            'razon_social' => ['required', 'string', 'max:255'],
            'rut'          => ['required', 'numeric',
                Rule::unique('inmobiliaria', 'rut')
                    ->when($inmobiliariaId !== null, fn($q) => $q->ignore($inmobiliariaId)),
            ],
            'direccion'    => ['required', 'string', 'max:255'],
            'telefono'     => ['required', 'string', 'regex:/^09\d{7}$/'],
            'logo_url'     => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'], 
        ];
    }

    protected function inmobiliariaMessages(): array
    {
        return [
            'razon_social.required' => 'La razón social es obligatoria.',
            'rut.required'          => 'El RUT es obligatorio.',
            'rut.unique'            => 'Este RUT ya está registrado en el sistema.',
            'rut.numeric'           => 'El RUT solo admite números.',
            'direccion.required'    => 'La dirección es obligatoria.',
            'telefono.required'     => 'El teléfono es obligatorio.',
            'telefono.regex'        => 'El teléfono debe comenzar con 09 y tener 9 dígitos (ej: 099123456).',
            'logo_url.image'       => 'El archivo debe ser una imagen.',
            'logo_url.max'         => 'El logo no debe superar los 2MB.',
            'logo_url.mimes'       => 'Solo se aceptan JPG, PNG o WEBP.',
        ];
    }
}
