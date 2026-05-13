<?php

namespace App\Http\Controllers\Inmobiliaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InmobiliariaController extends Controller
{
    public function dashboard()
    {
        // esta es la vista en la carpetita de inmobiliaria en resources/js/pages/inmobiliaria/, se accede desde el login si el usuario tiene el rol de inmobiliaria
        return inertia::render('inmobiliaria/Dashboard');
    }
}
