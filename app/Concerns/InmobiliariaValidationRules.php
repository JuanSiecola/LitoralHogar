<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait InmobiliariaValidationRules
{
    protected function inmobiliariaRules(): array
    {
        return [
            'razon_social' => ['required', 'string', 'max:255'],
            'rut'          => ['required', 'numeric', Rule::unique('inmobiliaria', 'rut')],
            'direccion'    => ['required', 'string', 'max:255'],
            'logo_url'     => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function inmobiliariaMessages(): array
    {
        return [
            'razon_social.required' => 'La razón social es obligatoria.',
            'rut.required'          => 'El RUT es obligatorio.',
            'rut.unique'            => 'Este RUT ya está registrado en el sistema.',
            'direccion.required'    => 'La dirección es obligatoria.',
            'logo_url.image'        => 'El logo debe ser una imagen válida.',
            'logo_url.max'          => 'El logo no puede superar los 2MB.',
        ];
    }
}
