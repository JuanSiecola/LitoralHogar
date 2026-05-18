<?php

namespace App\Http\Controllers\Propiedad;

use App\Actions\Propiedad\PropiedadCrearAction;
use App\Concerns\Propiedad\DetallePropiedadValidationRules;
use App\Concerns\Propiedad\PropiedadValidationRules;
use App\Concerns\Propiedad\UbicacionValidationRules;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Propiedad;
use App\Models\Amenidad;
use App\Actions\Propiedad\PropiedadEditAction;

class PropiedadController extends Controller
{
    use PropiedadValidationRules, UbicacionValidationRules, DetallePropiedadValidationRules;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $propiedades = Propiedad::where('usuario_id', auth()->id())
        ->with(['ubicacion', 'detalle_propiedad', 'imagenes'])
        ->get();

        return Inertia::render('inmobiliaria/propiedades/Index', [
            'propiedades' => $propiedades,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('inmobiliaria/propiedades/Create', [
            'amenidades' => Amenidad::all(['id', 'nombre']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(array_merge(
            $this->propiedadRules(),
            $this->ubicacionRules(),
            $this->detallePropiedadRules(),
            $this->imagenesRules(),
        ), array_merge(
            $this->propiedadMessages(),
            $this->ubicacionMessages(),
            $this->detallePropiedadMessages(),
            $this->imagenesMessages(),
        ));

        (new PropiedadCrearAction())->handle($validatedData, auth()->id());

        return redirect()->route('inmobiliaria.propiedades.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Propiedad $propiedad)
    {
        $validatedData = $request->validate(array_merge(
            $this->propiedadRules(),
            $this->ubicacionRules($propiedad->ubicacion->id),
            $this->detallePropiedadRules(),
        ), array_merge(
            $this->propiedadMessages(),
            $this->ubicacionMessages(),
            $this->detallePropiedadMessages(),
        ));

        (new PropiedadEditAction())->handle($propiedad, $validatedData);

        return redirect()->route('inmobiliaria.propiedades.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
