<?php
namespace App\Http\Responses;
use Illuminate\Support\Facades\Auth;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
class LoginResponse implements LoginResponseContract
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
            return redirect()->intended(route('home'));
        }
        if (in_array('agente', $roles)) {
            return redirect()->route('agente.dashboard');
        }

    }
}
