<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\PropiedadVista;
class PropiedadPublicaController extends Controller
{
    public function show(Request $request, Propiedad $propiedad)
    {
        $this->registrarVista($request, $propiedad);

        $propiedad->load([
            'detalle_propiedad',
            'ubicacion.departamento',
            'ubicacion.localidad',
            'imagenes',
            'amenidades',
            'usuario.inmobiliaria',
            'usuario.perfil_persona',
        ]);

        return Inertia::render('Propiedad/Show', [
            'propiedad' => [
                'id' => $propiedad->id,
                'es_favorito' => $request->user()
                    ? $request->user()->favoritos()->where('propiedad.id', $propiedad->id)->exists()
                    : false,
                'titulo' => $propiedad->titulo,
                'tipo_operacion' => $propiedad->tipo_operacion,
                'tipo_propiedad' => $propiedad->tipo_propiedad,
                'estado_propiedad' => $propiedad->estado_propiedad,
                'precio' => $propiedad->detalle_propiedad?->precio,
                'nro_habitaciones' => $propiedad->detalle_propiedad?->nro_habitaciones,
                'nro_banios' => $propiedad->detalle_propiedad?->nro_banios,
                'nro_garage' => $propiedad->detalle_propiedad?->nro_garage,
                'superficie_total' => $propiedad->detalle_propiedad?->superficie_total,
                'pisos' => $propiedad->detalle_propiedad?->pisos,
                'anio_construccion' => $propiedad->detalle_propiedad?->anio_construccion,
                'estado_construccion' => $propiedad->detalle_propiedad?->estado_construccion,
                'deposito' => $propiedad->detalle_propiedad?->deposito,
                'cant_meses_deposito' => $propiedad->detalle_propiedad?->cant_meses_deposito,
                'expensas' => $propiedad->detalle_propiedad?->expensas,
                'acepta_mascotas' => $propiedad->detalle_propiedad?->acepta_mascotas,
                'direccion' => $propiedad->ubicacion?->direccion,
                'localidad' => $propiedad->ubicacion?->localidad?->nombre,
                'departamento' => $propiedad->ubicacion?->departamento?->nombre,
                'latitud' => $propiedad->ubicacion?->latitud,
                'longitud' => $propiedad->ubicacion?->longitud,
                'imagenes' => $propiedad->imagenes->map(fn($img) => [
                    'id' => $img->id,
                    'url' => $img->url,
                    'es_principal' => $img->es_principal,
                ]),
                'amenidades' => $propiedad->amenidades->pluck('nombre'),
                'contacto' => $this->resolverContacto($propiedad),
            ],
        ]);
    }

    private function resolverContacto(Propiedad $propiedad): array
    {
        $usuario = $propiedad->usuario;
        if (!$usuario)
            return [];

        if ($usuario->inmobiliaria) {
            return [
                'tipo' => 'inmobiliaria',
                'nombre' => $usuario->inmobiliaria->nombre_comercial ?? $usuario->email,
                'email' => $usuario->email,
                'phone' => $usuario->inmobiliaria->telefono ?? null,
            ];
        }

        if ($usuario->perfil_persona) {
            return [
                'tipo' => 'agente',
                'nombre' => $usuario->perfil_persona->nombre . ' ' . $usuario->perfil_persona->apellido,
                'email' => $usuario->email,
                'phone' => $usuario->perfil_persona->telefono ?? null,
            ];
        }

        return [
            'tipo' => 'usuario',
            'nombre' => $usuario->email,
            'email' => $usuario->email,
            'phone' => null,
        ];
    }

    private function registrarVista(Request $request, Propiedad $propiedad): void
    {
        // No contamos las visitas del propio dueño a su aviso
        if ($request->user()?->id === $propiedad->usuario_id) {
            return;
        }

        PropiedadVista::firstOrCreate(
            [
                'propiedad_id' => $propiedad->id,
                'session_id' => $request->session()->getId(),
                'fecha' => now()->toDateString(),
            ],
            [
                'usuario_id' => $request->user()?->id,
                'ip' => $request->ip(),
            ],
        );
    }
}