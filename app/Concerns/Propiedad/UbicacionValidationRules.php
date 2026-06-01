<?php

namespace App\Concerns\Propiedad;

use Illuminate\Validation\Rule;

trait UbicacionValidationRules
{
    protected function ubicacionRules(?int $ubicacionId = null): array
    {
        return [
            'ubicacion.direccion'        => ['required', 'string', 'max:255'],
            'ubicacion.departamento_id'  => ['required', 'integer', 'exists:departamento,id'],
            'ubicacion.localidad_id'     => [
                'required',
                'integer',
                Rule::exists('localidad', 'id')->where(function ($query) {
                    $query->where('departamento_id', '=', request()->input('ubicacion.departamento_id'));
                }),
            ],
            'ubicacion.latitud'          => ['nullable', 'numeric', 'between:-90,90'],
            'ubicacion.longitud'         => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    protected function ubicacionMessages(): array
    {
        return [
            'ubicacion.direccion.required'        => 'La dirección es obligatoria.',
            'ubicacion.departamento_id.required'  => 'El departamento es obligatorio.',
            'ubicacion.departamento_id.exists'    => 'El departamento seleccionado no es válido.',
            'ubicacion.localidad_id.required'     => 'La localidad es obligatoria.',
            'ubicacion.localidad_id.exists'       => 'La localidad no corresponde al departamento seleccionado.',
            'ubicacion.latitud.numeric'           => 'La latitud debe ser un número.',
            'ubicacion.latitud.between'           => 'La latitud debe estar entre -90 y 90.',
            'ubicacion.longitud.numeric'          => 'La longitud debe ser un número.',
            'ubicacion.longitud.between'          => 'La longitud debe estar entre -180 y 180.',
        ];
    }
}