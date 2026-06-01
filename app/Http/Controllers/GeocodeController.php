<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeocodeController extends Controller
{
    public function buscar(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'direccion'    => ['required', 'string', 'max:255'],
            'localidad'    => ['nullable', 'string', 'max:100'],
            'departamento' => ['nullable', 'string', 'max:100'],
        ]);

        $cacheKey = 'geocode:' . md5(json_encode($validated));

        $resultado = Cache::remember($cacheKey, now()->addDays(7), function () use ($validated) {
            $response = Http::withHeaders([
                'User-Agent' => 'LitoralHogar/1.0 (proyecto-academico)',
            ])
            ->timeout(10)
            ->get('https://nominatim.openstreetmap.org/search', [
                'street'       => $validated['direccion'],
                'city'         => $validated['localidad'] ?? '',
                'state'        => $validated['departamento'] ?? '',
                'country'      => 'Uruguay',
                'countrycodes' => 'uy',
                'format'       => 'json',
                'limit'        => 1,
            ]);

            if (!$response->ok() || empty($response->json())) {
                return null;
            }

            $primero = $response->json()[0];

            return [
                'lat'          => (float) $primero['lat'],
                'lng'          => (float) $primero['lon'],
                'display_name' => $primero['display_name'] ?? null,
            ];
        });

        if (!$resultado) {
            return response()->json([
                'encontrado' => false,
                'mensaje'    => 'No se pudo encontrar la dirección.',
            ], 404);
        }

        return response()->json([
            'encontrado' => true,
            ...$resultado,
        ]);
    }
}