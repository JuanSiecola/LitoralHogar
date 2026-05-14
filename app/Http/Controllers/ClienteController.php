<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Consulta;
use App\Models\Favorito;
use App\Models\Propiedad;

class ClienteController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        return inertia('Cliente/Dashboard', [
            'totalFavoritos' => $user->favoritos()->count(),
            'totalConsultas' => $user->consultas()->count(),
            'consultasRecientes' => $user->consultas()->latest()->take(3)->with('propiedad')->get(),
        ]);
    }

    public function favoritos()
    {
        $favoritos = auth()->user()->favoritos()->with('imagenes')->paginate(12);
        return inertia('Cliente/Favoritos', compact('favoritos'));
    }

    public function quitarFavorito(Propiedad $propiedad)
    {
        auth()->user()->favoritos()->detach($propiedad->id);
        return back();
    }

    public function consultas()
    {
        return view('cliente.consultas');
    }
}
