<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\LocalidadResource;
use App\Models\Departamento;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class LocalidadController extends Controller
{
    public function porDepartamento(Departamento $departamento): AnonymousResourceCollection
    {
        $localidades = $departamento->localidades()->orderBy('nombre')->get();

        return LocalidadResource::collection($localidades);
    }
}
