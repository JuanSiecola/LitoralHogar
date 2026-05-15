<?php
namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
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

        // cliente y/o agente nos manda a landing page sape
        return redirect()->intended(route('home'));
    }
}
