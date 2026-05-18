<?php
namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user()->load('rol_usuario');

        $roles = $user->rol_usuario
            ->pluck('nombre')
            ->map(fn($r) => strtolower($r))
            ->toArray();

        if (in_array('inmobiliaria', $roles)) {
            
            return redirect()->intended(route('inmobiliaria.dashboard'));
        }
        if (in_array('cliente', $roles)) {
            return redirect()->intended(route('cliente.dashboard'));
        }
        if (in_array('agente', $roles)) {
            return redirect()->intended(route('agente.dashboard'));
        }
        // cliente y/o agente nos manda a landing page sape
        return redirect()->intended(route('home'));
    }
}
