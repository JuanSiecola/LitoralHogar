<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = DB::transaction(function () use ($googleUser) {
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                $user->update([
                    'google_id' => $user->google_id ?? $googleUser->getId(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);

                return $user;
            }

            $user = User::create([
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
                'password' => Str::password(32),
            ]);

            $clienteRolId = Rol::where('nombre', 'cliente')->value('id');

            if ($clienteRolId) {
                $user->rol_usuario()->attach($clienteRolId, ['fecha_asignacion' => now()]);
            }

            return $user;
        });

        Auth::login($user, remember: true);

        return redirect()->intended(route('home'));
    }
}
