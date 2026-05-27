<?php

namespace App\Http\Controllers\Inmobiliaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Inertia\Response;
class InmobiliariaController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('inmobiliaria/Dashboard');
    }

    public function perfil(Request $request): Response
    {
        return Inertia::render('inmobiliaria/Perfil', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

}
