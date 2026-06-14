<?php

namespace App\Http\Controllers;

use App\Services\EstadisticasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EstadisticasController extends Controller
{
    public function __construct(private EstadisticasService $stats)
    {
    }

    public function inmobiliaria(Request $request)
    {
        return $this->render('inmobiliaria/Estadisticas', $request);
    }

    public function agente(Request $request)
    {
        return $this->render('agente/Estadisticas', $request);
    }

    private function render(string $vista, Request $request)
    {
        $usuario = Auth::user();

        $dias = (int) $request->integer('dias', 30);
        $dias = in_array($dias, [30, 90, 180, 365], true) ? $dias : 30;

        // Lista de propiedades del usuario para llenar el <select>
        $propiedades = $usuario->propiedades()->get(['id', 'titulo']);

        // Validamos que la propiedad elegida sea SUYA (si no, la ignoramos = todas)
        $propiedadId = $request->integer('propiedad_id') ?: null;
        if ($propiedadId && !$propiedades->contains('id', $propiedadId)) {
            $propiedadId = null;
        }

        return Inertia::render($vista, [
            'estadisticas' => $this->stats->paraUsuario($usuario, $dias, $propiedadId),
            'dias' => $dias,
            'propiedades' => $propiedades,
            'propiedadId' => $propiedadId,
        ]);
    }
}