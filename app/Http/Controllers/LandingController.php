<?php

namespace App\Http\Controllers;
use App\Models\Propiedad;
use App\Models\Inmobiliaria;
use Inertia\Inertia;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Propiedades destacadas: las 6 más recientes con estado Disponible
        $propiedadesDestacadas = Propiedad::with([
                'detalle_propiedad',
                'ubicacion',
                'imagenes' => fn($q) => $q->where('es_principal', true)->limit(1),
            ])
            ->where('estado_propiedad', 'Disponible')
            ->orderByDesc('id')
            ->limit(6)
            ->get()
            ->map(fn($p) => [
                'id'               => $p->id,
                'titulo'           => $p->titulo,
                'tipo_operacion'   => $p->tipo_operacion,
                'tipo_propiedad'   => $p->tipo_propiedad,
                'precio'           => $p->detalle_propiedad?->precio,
                'nro_habitaciones' => $p->detalle_propiedad?->nro_habitaciones,
                'nro_banios'       => $p->detalle_propiedad?->nro_banios,
                'superficie_total' => $p->detalle_propiedad?->superficie_total,
                'ciudad'           => $p->ubicacion?->ciudad,
                'departamento'     => $p->ubicacion?->departamento,
                'imagen_url'       => $p->imagenes->first()?->url,
            ]);

        return Inertia::render('Landing', [
            'propiedadesDestacadas' => $propiedadesDestacadas
        ]);
    }
}
