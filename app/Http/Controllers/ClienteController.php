  <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Consulta;
use App\Models\Favorito;
use App\Models\Propiedad;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
class ClienteController extends Controller
{
    public function redirigirPropiedades()
    {
        $user = Auth::user()->loadMissing('rol_usuario');

        $esCliente = $user->rol_usuario
            ->contains(fn($rol) => strtolower($rol->nombre) === 'cliente');

        if (!$esCliente) {
            abort(403);
        }

        return redirect()->route('cliente.propiedades');
    }

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
        $consultas = auth()->user()
            ->consultas()
            ->with('propiedad')
            ->latest()
            ->paginate(12);
        return inertia('Cliente/Consultas', compact('consultas'));
    }

    public function propiedades()
    {
        $propiedades = Propiedad::with([
            'detalle_propiedad',
            'ubicacion',
            'imagenes' => fn($q) => $q->where('es_principal', true)->limit(1),
        ])
            ->where('estado_propiedad', 'Disponible')
            ->orderByDesc('fecha_publicacion')
            ->paginate(12)
            ->withQueryString()
            ->through(fn($propiedad) => [
                'id' => $propiedad->id,
                'titulo' => $propiedad->titulo,
                'tipo_operacion' => $propiedad->tipo_operacion,
                'tipo_propiedad' => $propiedad->tipo_propiedad,
                'precio' => $propiedad->detalle_propiedad?->precio ?? 0,
                'nro_habitaciones' => $propiedad->detalle_propiedad?->nro_habitaciones ?? 0,
                'nro_banios' => $propiedad->detalle_propiedad?->nro_banios ?? 0,
                'superficie_total' => $propiedad->detalle_propiedad?->superficie_total ?? 0,
                'localidad' => $propiedad->ubicacion?->localidad ?? 'Sin localidad',
                'departamento' => $propiedad->ubicacion?->departamento ?? 'Sin departamento',
                'latitud' => $propiedad->ubicacion?->latitud,
                'longitud' => $propiedad->ubicacion?->longitud,
                'imagen_url' => $propiedad->imagenes->first()?->url,
            ]);

        return inertia('Cliente/Propiedades', compact('propiedades'));
feature/proiedades-cliente
    } 
}
=======
    }
    public function perfil(Request $request): Response
    {
        return Inertia::render('Cliente/Perfil', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

}
 main
