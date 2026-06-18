<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\MensajeConsulta;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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

        $consulta = Consulta::create([
            'usuario_id' => $request->user()->id,
            'propiedad_id' => $validated['propiedad_id'],
        ]);

        $consulta->mensajes()->create([
            'usuario_id' => $request->user()->id,
            'mensaje' => $validated['mensaje'],
            'leido' => false,
        ]);

        return back()->with('success', 'Tu consulta fue enviada correctamente.');
    }

    public function enviarMensaje(Request $request, Consulta $consulta): RedirectResponse
    {
        $usuario = $request->user();

        $puedeEscribir = $consulta->usuario_id === $usuario->id
            || $consulta->propiedad?->usuario_id === $usuario->id;

        abort_if(!$puedeEscribir, 403);

        $validated = $request->validate([
            'mensaje' => ['required', 'string', 'max:2000'],
        ], [
            'mensaje.required' => 'Escribí un mensaje.',
            'mensaje.max' => 'El mensaje es demasiado largo.',
        ]);

        $consulta->mensajes()->create([
            'usuario_id' => $usuario->id,
            'mensaje' => $validated['mensaje'],
            'leido' => false,
        ]);

        return back()->with('success', 'Mensaje enviado.');
    }

    public function marcarLeido(Request $request, Consulta $consulta): RedirectResponse
    {
        $usuario = $request->user();

        $participa = $consulta->usuario_id === $usuario->id
            || $consulta->propiedad?->usuario_id === $usuario->id;

        abort_if(!$participa, 403);

        $consulta->mensajes()
            ->where('usuario_id', '!=', $usuario->id)
            ->update(['leido' => true]);

        return back();
    }
}