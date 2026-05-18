<?php

namespace App\Concerns\Propiedad;

use Illuminate\Validation\Rule;

trait PropiedadValidationRules
{
    protected function propiedadRules(?int $propiedadId = null): array
    {
        return [
            'titulo'       => ['required', 'string', 'max:255'],
            'estado_propiedad' => ['required', 'in:Disponible,Vendida,Alquilada,Reservada,Pausada'],
            'tipo_propiedad' => ['required', 'string', 'in:Casa,Apartamento,Terreno,Local,Oficina'],
            'tipo_operacion'  => ['required', 'in:Venta,Alquiler'], 
        ];
    }

    protected function propiedadMessages(): array
    {
        return [
            'titulo.required'       => 'El título es obligatorio.',
            'tipo_propiedad.required' => 'El tipo de propiedad es obligatorio.',
            'tipo_propiedad.in'     => 'El tipo de propiedad debe ser uno de los siguientes: Casa, Apartamento, Terreno, Local, Oficina.',
            'estado_propiedad.required' => 'El estado de la propiedad es obligatorio.',
            'estado_propiedad.in'   => 'El estado de la propiedad debe ser uno de los siguientes: Disponible, Vendida, Alquilada, Reservada, Pausada.',
            'tipo_operacion.required'  => 'El tipo de operación es obligatorio.',
            'tipo_operacion.in'      => 'El tipo de operación debe ser Venta o Alquiler.',
        ];
    }
}