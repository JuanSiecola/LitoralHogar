<?php

namespace App\Concerns\Propiedad;

use Illuminate\Validation\Rule;

trait DetallePropiedadValidationRules
{
    protected function detallePropiedadRules(?int $propiedadId = null): array
    {
        return [
            'nro_habitaciones' => ['required', 'integer', 'min:0', 'max:20'],
            'nro_banios' => ['required', 'integer', 'min:0', 'max:10'],
            'nro_garage' => ['required', 'integer', 'min:0', 'max:5'],
            'superficie_total' => ['required', 'numeric', 'min:1'],
            'pisos' => ['required', 'integer', 'min:1'],
            'precio' => ['required', 'numeric', 'min:0'],
            'anio_construccion' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'estado_construccion' => ['required', 'in:Nuevo,Usado,Reciclado'],
            'deposito' => ['nullable', 'numeric', 'min:0'],
            'cant_meses_deposito' => ['nullable', 'integer', 'min:1'],
            'expensas' => ['nullable', 'numeric', 'min:0'],
            'acepta_mascotas' => ['nullable', 'boolean'],
            'amenidades'   => ['nullable', 'array'],
            'amenidades.*' => ['integer', 'exists:amenidad,id'],
        ];
    }

    protected function detallePropiedadMessages(): array
    {
        return [
            'nro_habitaciones.required' => 'El número de habitaciones es obligatorio.',
            'nro_habitaciones.integer' => 'El número de habitaciones debe ser un número entero.',
            'nro_habitaciones.min' => 'El número de habitaciones no puede ser negativo.',
            'nro_habitaciones.max' => 'El número de habitaciones no puede ser mayor a 20.',
            'nro_banios.required' => 'El número de baños es obligatorio.',
            'nro_banios.integer' => 'El número de baños debe ser un número entero.',
            'nro_banios.min' => 'El número de baños no puede ser negativo.',
            'nro_banios.max' => 'El número de baños no puede ser mayor a 10.',
            'nro_garage.required' => 'El número de garage es obligatorio.',
            'nro_garage.integer' => 'El número de garage debe ser un número entero.',
            'nro_garage.min' => 'El número de garage no puede ser negativo.',
            'nro_garage.max' => 'El número de garage no puede ser mayor a 5.',
            'superficie_total.required' => 'La superficie total es obligatoria.',
            'superficie_total.numeric' => 'La superficie total debe ser un número.',
            'superficie_total.min' => 'La superficie total debe ser al menos 1 m².',
            'pisos.required' => 'El número de pisos es obligatorio.',
            'pisos.integer' => 'El número de pisos debe ser un número entero.',
            'pisos.min' => 'El número de pisos debe ser al menos 1.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio no puede ser negativo.',
            'anio_construccion.required' => 'El año de construcción es obligatorio.',
            'anio_construccion.integer' => 'El año de construcción debe ser un número entero.',
            'anio_construccion.min' => 'El año de construcción no puede ser anterior a 1900.',
            'anio_construccion.max' => 'El año de construcción no puede ser posterior al año actual.',
            'estado_construccion.required' => 'El estado de construcción es obligatorio.',
            'estado_construccion.in' => 'El estado de construcción debe ser Nuevo, Usado o Reciclar.',
            'deposito.numeric' => 'El depósito debe ser un número.',
            'deposito.min' => 'El depósito no puede ser negativo.',
            'cant_meses_deposito.integer' => 'La cantidad de meses de depósito debe ser un número entero.',
            'cant_meses_deposito.min' => 'La cantidad de meses de depósito debe ser al menos 1.',
            'expensas.numeric' => 'Las expensas deben ser un número.',
            'expensas.min' => 'Las expensas no pueden ser negativas.',
            'acepta_mascotas.required' => 'El campo acepta mascotas es obligatorio.',
            'acepta_mascotas.boolean' => 'El campo acepta mascotas debe ser verdadero o falso.',
            'amenidades.array' => 'Las amenidades deben ser un array.',
            'amenidades.*.integer' => 'Cada amenidad debe ser un número entero.',
            'amenidades.*.exists' => 'La amenidad seleccionada no existe.',
        ];
    }
}