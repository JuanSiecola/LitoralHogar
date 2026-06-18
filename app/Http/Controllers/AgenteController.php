<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use App\Models\Propiedad;
use App\Models\Amenidad;
use App\Actions\Propiedad\PropiedadCrearAction;
use App\Actions\Propiedad\PropiedadEditAction;
use App\Concerns\Propiedad\PropiedadValidationRules;
use App\Concerns\Propiedad\UbicacionValidationRules;
use App\Concerns\Propiedad\DetallePropiedadValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Departamento;

class AgenteController extends Controller
{
    use PropiedadValidationRules;
    use UbicacionValidationRules;
    use DetallePropiedadValidationRules;

    public function dashboard()
    {
        $agente = Auth::user();

        $propsActivas = $agente->propiedades()
            ->where('estado_propiedad', 'Disponible')
            ->count();


        $consultasPendientes = Consulta::whereHas('propiedad', fn($q) => $q->where('usuario_id', $agente->id))
            ->where('estado', 'pendiente')
            ->count();

        $ultimasConsultas = Consulta::whereHas('propiedad', fn($q) => $q->where('usuario_id', $agente->id))
            ->with(['user.perfil_persona:id,nombre,apellido,usuario_id', 'propiedad:id,titulo'])
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('agente/Dashboard', [
            'agente' => [
                'id' => $agente->id,
                'nombre' => $agente->perfilPersona?->nombre,
            ],
            'propsActivas' => $propsActivas,
            'consultasPendientes' => $consultasPendientes,
            'ultimasConsultas' => $ultimasConsultas,
        ]);
    }
    public function propiedades()
    {
        $propiedades = Auth::user()
            ->propiedades()
            ->with([
                'ubicacion.departamento:id,nombre',
                'ubicacion.localidad:id,nombre',
                'detalle_propiedad',
                'imagenes',
            ])
            ->get();

        return Inertia::render('agente/Propiedades/Index', [
            'propiedades' => $propiedades,
        ]);
    }

    // Listar consultas recibidas en propiedades del agente
    public function consultasRecibidas()
    {
        $consultas = Consulta::whereIn('propiedad_id', auth()->user()->propiedades()->pluck('id'))
            ->with(['propiedad', 'user.perfil_persona:id,nombre,apellido,usuario_id'])
            ->latest()
            ->paginate(15);

        return inertia('agente/ConsultasRecibidas', compact('consultas'));
    }

    // Endpoint para responder
    public function responderConsulta(Request $request, Consulta $consulta)
    {
        $request->validate(['respuesta' => 'required|string|max:1000']);

        $consulta->update([
            'respuesta' => $request->respuesta,
            'estado' => 'respondida',
        ]);

        return back()->with('success', 'Respuesta enviada.');
    }

    public function create()
    {
        return inertia('agente/Propiedades/Create', [
            'amenidades' => Amenidad::all('id', 'nombre'),
            'departamentos' => Departamento::all('id', 'nombre'),
        ]);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate(array_merge(
            $this->propiedadRules(),
            $this->ubicacionRules(),
            $this->detallePropiedadRules(),
            $this->imagenesRules(),
        ), array_merge(
            $this->propiedadMessages(),
            $this->ubicacionMessages(),
            $this->detallePropiedadMessages(),
            $this->imagenesMessages(),
        ));

        (new PropiedadCrearAction())->handle($validatedData, auth()->id());

        return redirect()->route('agente.propiedades')->with('success', 'Propiedad creada exitosamente.');
    }

    public function edit(Propiedad $propiedad)
    {
        // Verificar que la propiedad pertenece al agente autenticado
        abort_if($propiedad->usuario_id !== auth()->id(), 403);

        $propiedad->load(['ubicacion', 'detalle_propiedad', 'imagenes', 'amenidades']);

        return inertia('agente/Propiedades/Edit', [
            'propiedad' => [
                'id' => $propiedad->id,
                'titulo' => $propiedad->titulo,
                'tipo_propiedad' => $propiedad->tipo_propiedad,
                'tipo_operacion' => $propiedad->tipo_operacion,
                'estado_propiedad' => $propiedad->estado_propiedad,
                'ubicacion' => [
                    'direccion' => $propiedad->ubicacion->direccion,
                    'localidad_id' => $propiedad->ubicacion->localidad_id,
                    'departamento_id' => $propiedad->ubicacion->departamento_id,
                    'latitud' => $propiedad->ubicacion->latitud,
                    'longitud' => $propiedad->ubicacion->longitud,
                ],
                'detalle_propiedad' => [
                    'nro_habitaciones' => $propiedad->detalle_propiedad->nro_habitaciones,
                    'nro_banios' => $propiedad->detalle_propiedad->nro_banios,
                    'nro_garage' => $propiedad->detalle_propiedad->nro_garage,
                    'superficie_total' => $propiedad->detalle_propiedad->superficie_total,
                    'pisos' => $propiedad->detalle_propiedad->pisos,
                    'precio' => $propiedad->detalle_propiedad->precio,
                    'anio_construccion' => $propiedad->detalle_propiedad->anio_construccion,
                    'estado_construccion' => $propiedad->detalle_propiedad->estado_construccion,
                    'deposito' => $propiedad->detalle_propiedad->deposito,
                    'cant_meses_deposito' => $propiedad->detalle_propiedad->cant_meses_deposito,
                    'expensas' => $propiedad->detalle_propiedad->expensas,
                    'acepta_mascotas' => $propiedad->detalle_propiedad->acepta_mascotas,
                ],
                // objetos con id y nombre, NO solo IDs
                'amenidades' => $propiedad->amenidades->map(fn($a) => [
                    'id' => $a->id,
                    'nombre' => $a->nombre,
                ]),
                'imagenes' => $propiedad->imagenes->map(fn($i) => [
                    'id' => $i->id,
                    'url' => $i->url,
                    'es_principal' => $i->es_principal,
                    'orden' => $i->orden ?? 0,
                ]),
            ],
            'amenidades' => Amenidad::all(['id', 'nombre']),
            'departamentos' => Departamento::all(['id', 'nombre']),
        ]);
    }

    public function update(Request $request, Propiedad $propiedad)
    {
        // Verificar que la propiedad pertenece al agente autenticado
        abort_if($propiedad->usuario_id !== auth()->id(), 403);

        if ($request->has('estado_propiedad') && count($request->all()) === 1) {
            $propiedad->update(['estado_propiedad' => $request->estado_propiedad]);
            return back();
        }

        $validatedData = $request->validate(array_merge(
            $this->propiedadRules(),
            $this->ubicacionRules($propiedad->ubicacion->id),
            $this->detallePropiedadRules(),
        ), array_merge(
            $this->propiedadMessages(),
            $this->ubicacionMessages(),
            $this->detallePropiedadMessages(),
        ));

        (new PropiedadEditAction())->handle($propiedad, $validatedData);

        return redirect()->route('agente.propiedades')->with('success', 'Propiedad actualizada exitosamente.');
    }

    public function destroy(Propiedad $propiedad)
    {
        // Verificar que la propiedad pertenece al agente autenticado
        abort_if($propiedad->usuario_id !== auth()->id(), 403);
        $propiedad->delete();

        return redirect()->route('agente.propiedades')->with('success', 'Propiedad eliminada exitosamente.');
    }

    public function perfil(Request $request): Response
    {
        return Inertia::render('agente/Perfil', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }


}
