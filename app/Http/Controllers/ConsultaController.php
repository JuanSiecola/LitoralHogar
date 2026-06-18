<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Propiedad;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ConsultaController extends Controller
{
    
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'propiedad_id' => ['required', 'integer', 'exists:propiedad,id'],
            'mensaje' => ['required', 'string', 'max:2000'],
        ], [
            'propiedad_id.required' => 'No se especificó la propiedad.',
            'propiedad_id.exists' => 'La propiedad no existe.',
            'mensaje.required' => 'Escribí un mensaje para tu consulta.',
            'mensaje.max' => 'El mensaje es demasiado largo.',
        ]);

        Consulta::create([
            'usuario_id' => $request->user()->id,
            'propiedad_id' => $validated['propiedad_id'],
            'mensaje' => $validated['mensaje'],
            'estado' => 'pendiente',
        ]);

        return back()->with('success', 'Tu consulta fue enviada correctamente.');
    }
}