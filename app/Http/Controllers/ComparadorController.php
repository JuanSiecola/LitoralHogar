<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Illuminate\Http\Request;

class ComparadorController extends Controller
{
    public function comparar(Request $request)
    {
        $ids = array_slice($request->query('ids', []), 0, 2);

        $propiedades = Propiedad::with([
            'detalle_propiedad',
            'ubicacion.localidad',
            'ubicacion.departamento',
            'imagenes',
            'amenidades',
        ])
        ->whereIn('id', $ids)
        ->get()
        ->map(function ($p) {
            $d = $p->detalle_propiedad;
            $u = $p->ubicacion;
            return [
                'id'                  => $p->id,
                'titulo'              => $p->titulo,
                'tipo_operacion'      => $p->tipo_operacion,
                'tipo_propiedad'      => $p->tipo_propiedad,
                'estado_propiedad'    => $p->estado_propiedad,
                'precio'              => $d?->precio,
                'nro_habitaciones'    => $d?->nro_habitaciones,
                'nro_banios'          => $d?->nro_banios,
                'nro_garage'          => $d?->nro_garage,
                'superficie_total'    => $d?->superficie_total,
                'pisos'               => $d?->pisos,
                'anio_construccion'   => $d?->anio_construccion,
                'estado_construccion' => $d?->estado_construccion,
                'acepta_mascotas'     => $d?->acepta_mascotas,
                'expensas'            => $d?->expensas,
                'deposito'            => $d?->deposito,
                'direccion'           => $u?->direccion,
                'localidad'           => $u?->localidad?->nombre,
                'departamento'        => $u?->departamento?->nombre,
                'imagen_principal'    => $p->imagenes->firstWhere('es_principal', true)?->url
                                         ?? $p->imagenes->first()?->url,
                'amenidades'          => $p->amenidades->pluck('nombre'),
            ];
        });

        return inertia('Propiedad/Comparar', [
            'propiedades' => $propiedades,
        ]);
    }
}