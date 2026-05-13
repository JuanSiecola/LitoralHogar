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
            // TODO: cambiar a route('inmobiliaria.dashboard') cuando esté implementado
            return redirect()->intended(route('dashboard'));
        }

        // cliente y/o agente nos manda a landing page sape
        return redirect()->intended(route('home'));
    }
}
