<?php

namespace App\Http\Controllers\Inmobiliaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Inertia\Response;
use App\Models\Consulta;
use Illuminate\Support\Facades\Auth;
class InmobiliariaController extends Controller
{
    public function dashboard()
    {
        $usuario = Auth::user();

        $propsActivas = $usuario->propiedades()
            ->where('estado_propiedad', 'disponible')
            ->count();

        $propsAlquiladas = $usuario->propiedades()
            ->where('estado_propiedad', 'alquilada')
            ->count();

        $consultasPendientes = Consulta::whereHas('propiedad', fn($q) => $q->where('usuario_id', $usuario->id))
            ->where('estado', 'pendiente')
            ->count();

        // 3. Obtener las últimas 5 consultas
        $ultimasConsultas = Consulta::whereHas('propiedad', fn($q) => $q->where('usuario_id', $usuario->id))
            ->with(['user.perfil_persona:id,nombre,apellido,usuario_id', 'propiedad:id,titulo'])
            ->latest()
            ->take(5)
            ->get();

        // 4. Enviar los datos a la vista y de paso extraer la inmobiliaria
        return Inertia::render('inmobiliaria/Dashboard', [
            'inmobiliaria' => [
                'id' => $usuario->inmobiliaria?->id,
                // Tu modelo usa 'razon_social'
                'razonSocial' => $usuario->inmobiliaria?->razon_social,
            ],
            'propsActivas' => $propsActivas,
            'propsAlquiladas' => $propsAlquiladas,
            'consultasPendientes' => $consultasPendientes,
            'ultimasConsultas' => $ultimasConsultas,
        ]);
    }

    public function consultasRecibidas()
    {
        $usuario = Auth::user();

        $consultas = Consulta::whereHas('propiedad', fn($q) => $q->where('usuario_id', $usuario->id))
            ->with([
                'user.perfil_persona:id,nombre,apellido,usuario_id',
                'propiedad:id,titulo',
                'mensajes.user.perfil_persona:id,nombre,apellido,usuario_id',
            ])
            ->latest()
            ->paginate(10);

        $consultas->getCollection()->transform(function ($consulta) use ($usuario) {
            $consulta->no_leidos = $consulta->noLeidosPara($usuario->id);
            return $consulta;
        });

        return Inertia::render('inmobiliaria/ConsultasRecibidas', compact('consultas'));
    }

    public function perfil(Request $request): Response
    {
        return Inertia::render('inmobiliaria/Perfil', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

}