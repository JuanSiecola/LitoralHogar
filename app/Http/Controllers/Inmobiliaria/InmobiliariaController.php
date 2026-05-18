<?php

namespace App\Http\Controllers\Inmobiliaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InmobiliariaController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('inmobiliaria/Dashboard');
    }
}
