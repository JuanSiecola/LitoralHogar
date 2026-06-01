<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Inertia\Inertia;
use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactNotification;
use Illuminate\Support\Facades\Mail;

class LandingController extends Controller
{
    public function index()
    {
        // Propiedades destacadas: las 6 más recientes con estado Disponible
        $propiedadesDestacadas = Propiedad::with([
            'detalle_propiedad',
            'ubicacion.departamento:id,nombre',
            'ubicacion.localidad:id,nombre',
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
                'localidad'        => $p->ubicacion?->localidad?->nombre,
                'departamento'     => $p->ubicacion?->departamento?->nombre,
                'imagen_url'       => $p->imagenes->first()?->url,
            ]);
        $categorias = Propiedad::where('estado_propiedad', 'Disponible')
            ->select('tipo_propiedad', 'tipo_operacion')
            ->distinct()
            ->orderBy('tipo_propiedad')
            ->get();

        return Inertia::render('Landing', [
            'propiedadesDestacadas' => $propiedadesDestacadas,
                'categorias'            => $categorias
        ]);
    }

    public function sendContact(ContactFormRequest $request)
    {
        $data = $request->validated();

        // Enviar email
        Mail::to(config('mail.from.address'))->send(new ContactNotification($data));

        return back()->with('success', '¡Consulta enviada correctamente! Te contactaremos pronto.');
    }
}
