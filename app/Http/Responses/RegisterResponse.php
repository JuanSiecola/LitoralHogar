<?php
namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Support\Facades\Auth;
class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user()->load('rol_usuario');

        $roles = $user->rol_usuario
            ->pluck('nombre')
            ->map(fn($r) => strtolower($r))
            ->toArray();

        if (in_array('inmobiliaria', $roles)) {
            return redirect()->route('inmobiliaria.dashboard');
        }
        if (in_array('cliente', $roles)) {
            return redirect()->route('cliente.dashboard');
        }
        if (in_array('agente', $roles)) {
            return redirect()->route('agente.dashboard');
        }
    }
}
