<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Propiedad;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ClienteController extends Controller
{
    public function redirigirPropiedades(Request $request)
    {
        $user = Auth::user()->loadMissing('rol_usuario');

        $esCliente = $user->rol_usuario
            ->contains(fn($rol) => strtolower($rol->nombre) === 'cliente');

        if (!$esCliente) {
            abort(403);
        }

        return redirect()->route('cliente.propiedades', $request->query());
    }

    public function dashboard()
    {
        $user = Auth::user();

        return inertia('Cliente/Dashboard', [
            'totalFavoritos' => $user->favoritos()->count(),
            'totalConsultas' => $user->consultas()->count(),
            'consultasRecientes' => $user->consultas()->latest()->take(3)->with('propiedad')->get(),
        ]);
    }

    public function favoritos()
    {
        $favoritos = auth()->user()->favoritos()->with('imagenes')->paginate(12);

        return inertia('Cliente/Favoritos', compact('favoritos'));
    }

    public function quitarFavorito(Propiedad $propiedad)
    {
        auth()->user()->favoritos()->detach($propiedad->id);

        return back();
    }

    public function consultas()
    {
        $consultas = auth()->user()
            ->consultas()
            ->with('propiedad')
            ->latest()
            ->paginate(12);

        return inertia('Cliente/Consultas', compact('consultas'));
    }

    public function propiedades(Request $request)
    {
        $filters = $request->only([
            'tipo_operacion',
            'tipo_propiedad',
            'localidad',
            'departamento',
            'nro_habitaciones',
            'nro_banios',
            'precio_min',
            'precio_max',
            'superficie_min',
        ]);

        $propiedades = Propiedad::query()
            ->with([
                'detalle_propiedad',
                'ubicacion',
                'imagenes' => fn($q) => $q->where('es_principal', true)->limit(1),
            ])
            ->where('estado_propiedad', 'Disponible')
            ->when($request->filled('tipo_operacion'), fn($query) => $query
                ->where('tipo_operacion', (string) $request->input('tipo_operacion')))
            ->when($request->filled('tipo_propiedad'), fn($query) => $query
                ->where('tipo_propiedad', (string) $request->input('tipo_propiedad')))
            ->when($request->filled('localidad'), fn($query) => $query
                ->whereHas('ubicacion', fn($ubicacion) => $ubicacion
                    ->where('localidad', 'like', '%' . $request->input('localidad') . '%')))
            ->when($request->filled('departamento'), fn($query) => $query
                ->whereHas('ubicacion', fn($ubicacion) => $ubicacion
                    ->where('departamento', 'like', '%' . $request->input('departamento') . '%')))
            ->when(
                $request->filled('nro_habitaciones')
                || $request->filled('nro_banios')
                || $request->filled('precio_min')
                || $request->filled('precio_max')
                || $request->filled('superficie_min'),
                fn($query) => $query->whereHas('detalle_propiedad', function ($detalle) use ($request) {
                    $detalle
                        ->when($request->filled('nro_habitaciones'), fn($detalleQuery) => $detalleQuery
                            ->where('nro_habitaciones', '>=', (int) $request->input('nro_habitaciones')))
                        ->when($request->filled('nro_banios'), fn($detalleQuery) => $detalleQuery
                            ->where('nro_banios', '>=', (int) $request->input('nro_banios')))
                        ->when($request->filled('precio_min'), fn($detalleQuery) => $detalleQuery
                            ->where('precio', '>=', (float) $request->input('precio_min')))
                        ->when($request->filled('precio_max'), fn($detalleQuery) => $detalleQuery
                            ->where('precio', '<=', (float) $request->input('precio_max')))
                        ->when($request->filled('superficie_min'), fn($detalleQuery) => $detalleQuery
                            ->where('superficie_total', '>=', (float) $request->input('superficie_min')));
                })
            )
            ->orderByDesc('fecha_publicacion')
            ->paginate(12)
            ->withQueryString()
            ->through(fn($propiedad) => [
                'id' => $propiedad->id,
                'titulo' => $propiedad->titulo,
                'tipo_operacion' => $propiedad->tipo_operacion,
                'tipo_propiedad' => $propiedad->tipo_propiedad,
                'precio' => $propiedad->detalle_propiedad?->precio ?? 0,
                'nro_habitaciones' => $propiedad->detalle_propiedad?->nro_habitaciones ?? 0,
                'nro_banios' => $propiedad->detalle_propiedad?->nro_banios ?? 0,
                'superficie_total' => $propiedad->detalle_propiedad?->superficie_total ?? 0,
                'localidad' => $propiedad->ubicacion?->localidad ?? 'Sin localidad',
                'departamento' => $propiedad->ubicacion?->departamento ?? 'Sin departamento',
                'latitud' => $propiedad->ubicacion?->latitud,
                'longitud' => $propiedad->ubicacion?->longitud,
                'imagen_url' => $propiedad->imagenes->first()?->url,
            ]);

        return inertia('Cliente/Propiedades', [
            'propiedades' => $propiedades,
            'filters' => $filters,
        ]);
    }

    public function perfil(Request $request): Response
    {
        return Inertia::render('Cliente/Perfil', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }
}
