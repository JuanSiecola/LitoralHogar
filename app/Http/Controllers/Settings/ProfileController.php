<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileDeleteRequest;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->loadMissing(['rol_usuario', 'perfil_persona', 'inmobiliaria']);
        $validated = $request->validated();

        if ($user->email !== $validated['email']) {
            $user->email = $validated['email'];
            $user->email_verified_at = null;
            $user->save();
        }

        $isInmobiliaria = $user->rol_usuario
            ->contains(fn($r) => str_contains(strtolower($r->nombre), 'inmobiliaria'));

        if ($isInmobiliaria) {
            $user->inmobiliaria()->updateOrCreate([], [
                'razon_social' => $validated['razon_social'],
                'rut' => $validated['rut'],
                'direccion' => $validated['direccion'],
                'telefono' => $validated['telefono'],
            ]);
        } else {
            $user->perfil_persona()->updateOrCreate([], [
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'cedula' => $validated['cedula'] ?? null,
                'telefono' => $validated['telefono'],
            ]);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Perfil actualizado correctamente.']);

        $isAgente = $user->rol_usuario->contains(fn($r) => str_contains(strtolower($r->nombre), 'agente'));
        $isCliente = $user->rol_usuario->contains(fn($r) => str_contains(strtolower($r->nombre), 'cliente'));

        if ($isInmobiliaria)
            return to_route('inmobiliaria.perfil');
        if ($isAgente)
            return to_route('agente.perfil');
        if ($isCliente)
            return to_route('cliente.perfil');
        return back();
    }

    public function destroy(ProfileDeleteRequest $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
