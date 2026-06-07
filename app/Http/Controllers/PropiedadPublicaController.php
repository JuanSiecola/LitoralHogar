<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Inertia\Inertia;

class PropiedadPublicaController extends Controller
{
    public function show(Propiedad $propiedad)
    {
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
                'id'                  => $propiedad->id,
                'titulo'              => $propiedad->titulo,
                'tipo_operacion'      => $propiedad->tipo_operacion,
                'tipo_propiedad'      => $propiedad->tipo_propiedad,
                'estado_propiedad'    => $propiedad->estado_propiedad,
                'precio'              => $propiedad->detalle_propiedad?->precio,
                'nro_habitaciones'    => $propiedad->detalle_propiedad?->nro_habitaciones,
                'nro_banios'          => $propiedad->detalle_propiedad?->nro_banios,
                'nro_garage'          => $propiedad->detalle_propiedad?->nro_garage,
                'superficie_total'    => $propiedad->detalle_propiedad?->superficie_total,
                'pisos'               => $propiedad->detalle_propiedad?->pisos,
                'anio_construccion'   => $propiedad->detalle_propiedad?->anio_construccion,
                'estado_construccion' => $propiedad->detalle_propiedad?->estado_construccion,
                'deposito'            => $propiedad->detalle_propiedad?->deposito,
                'cant_meses_deposito' => $propiedad->detalle_propiedad?->cant_meses_deposito,
                'expensas'            => $propiedad->detalle_propiedad?->expensas,
                'acepta_mascotas'     => $propiedad->detalle_propiedad?->acepta_mascotas,
                'direccion'           => $propiedad->ubicacion?->direccion,
                'localidad'           => $propiedad->ubicacion?->localidad?->nombre,
                'departamento'        => $propiedad->ubicacion?->departamento?->nombre,
                'latitud'             => $propiedad->ubicacion?->latitud,
                'longitud'            => $propiedad->ubicacion?->longitud,
                'imagenes'            => $propiedad->imagenes->map(fn($img) => [
                    'id'           => $img->id,
                    'url'          => $img->url,
                    'es_principal' => $img->es_principal,
                ]),
                'amenidades'          => $propiedad->amenidades->pluck('nombre'),
                'contacto'            => $this->resolverContacto($propiedad),
            ],
        ]);
    }

    private function resolverContacto(Propiedad $propiedad): array
    {
        $usuario = $propiedad->usuario;
        if (!$usuario) return [];

        if ($usuario->inmobiliaria) {
            return [
                'tipo'   => 'inmobiliaria',
                'nombre' => $usuario->inmobiliaria->nombre_comercial ?? $usuario->email,
                'email'  => $usuario->email,
                'phone'  => $usuario->inmobiliaria->telefono ?? null,
            ];
        }

        if ($usuario->perfil_persona) {
            return [
                'tipo'   => 'agente',
                'nombre' => $usuario->perfil_persona->nombre . ' ' . $usuario->perfil_persona->apellido,
                'email'  => $usuario->email,
                'phone'  => $usuario->perfil_persona->telefono ?? null,
            ];
        }

        return [
            'tipo'   => 'usuario',
            'nombre' => $usuario->email,
            'email'  => $usuario->email,
            'phone'  => null,
        ];
    }
}