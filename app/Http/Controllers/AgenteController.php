<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta; 


class AgenteController extends Controller
{

    public function dashboard()
    {
        $agente = auth()->user();
        return inertia('agente/Dashboard', [
            'propsActivas' => $agente->propiedades()->where('estado_propiedad', 'Disponible')->count(),
            'totalVistas'  => 0 ,  /* $agente->propiedades()->sum('calificacion'), */
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



}
