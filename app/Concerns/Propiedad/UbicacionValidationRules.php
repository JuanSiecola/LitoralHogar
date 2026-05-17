<?php

namespace App\Concerns\Propiedad;

use Illuminate\Validation\Rule;

trait UbicacionValidationRules
{
    protected function ubicacionRules(?int $ubicacionId = null): array
    {
        return [
            'direccion'    => ['required', 'string', 'max:255'],
            'ciudad'       => ['required', 'string', 'max:100'],
            'departamento' => ['required', 'string', 'max:100'],
            'latitud'      => ['nullable', 'numeric'],
            'longitud'     => ['nullable', 'numeric'],
        ];
    }

    protected function ubicacionMessages(): array
    {
        return [
            'direccion.required' => 'La dirección es obligatoria.',
            'ciudad.required' => 'La ciudad es obligatoria.',
            'departamento.required' => 'El departamento es obligatorio.',
            'latitud.numeric' => 'La latitud debe ser un número.',
            'longitud.numeric' => 'La longitud debe ser un número.',
        ];
    }
}
