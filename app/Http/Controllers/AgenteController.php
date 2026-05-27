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


class AgenteController extends Controller
{
    use PropiedadValidationRules;
    use UbicacionValidationRules;
    use DetallePropiedadValidationRules;

    public function dashboard()
    {
        $agente = auth()->user();
        return inertia('agente/Dashboard', [
            'propsActivas' => $agente->propiedades()->where('estado_propiedad', 'Disponible')->count(),
            'totalVistas' => 0, /* $agente->propiedades()->sum('calificacion'), */
            'consultasPendientes' => Consulta::whereIn(
                'propiedad_id',
                $agente->propiedades()->pluck('id')
            )->where('estado', 'pendiente')->count(),
        ]);
    }

    public function propiedades()
    {
        $propiedades = auth()->user()
            ->propiedades()
            ->with('imagenes', 'detalle_propiedad', 'ubicacion')
            ->when(request('estado'), fn($q, $e) => $q->where('estado_propiedad', $e))
            ->paginate(15);

        return inertia('agente/Propiedades', compact('propiedades'));
    }

    // Listar consultas recibidas en propiedades del agente
    public function consultasRecibidas()
    {
        $consultas = Consulta::whereIn('propiedad_id', auth()->user()->propiedades()->pluck('id'))
            ->with(['propiedad', 'user'])
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
        return inertia('agente/PropiedadForm', [
            'propiedad' => null,
            'amenidades' => Amenidad::all('id', 'nombre'),
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
        $this->authorize('update', $propiedad);

        return inertia('agente/PropiedadForm', [
            'propiedad' => [
                'id' => $propiedad->id,
                'titulo' => $propiedad->titulo,
                'tipo_propiedad' => $propiedad->tipo_propiedad,
                'tipo_operacion' => $propiedad->tipo_operacion,
                'estado_propiedad' => $propiedad->estado_propiedad,
                'direccion' => $propiedad->ubicacion->direccion,
                'ciudad' => $propiedad->ubicacion->ciudad,
                'departamento' => $propiedad->ubicacion->departamento,
                'latitud' => $propiedad->ubicacion->latitud,
                'longitud' => $propiedad->ubicacion->longitud,
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
                'imagenes' => $propiedad->imagenes,
                'amenidades' => $propiedad->amenidades->pluck('id')->toArray(),
            ],
            'amenidades' => Amenidad::all(['id', 'nombre']),
        ]);
    }

    public function update(Request $request, Propiedad $propiedad)
    {
        // Verificar que la propiedad pertenece al agente autenticado
        $this->authorize('update', $propiedad);

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
        $this->authorize('delete', $propiedad);

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
