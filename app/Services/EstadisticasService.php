<?php

namespace App\Services;

use App\Models\Propiedad;
use App\Models\User;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class EstadisticasService
{
    public function paraUsuario(User $usuario, int $dias = 30, ?int $propiedadId = null): array
    {
        $propIds = $usuario->propiedades()->pluck('id');

        if ($propiedadId !== null) {
            $propIds = $propIds->filter(fn($id) => $id === $propiedadId)->values();
        }

        $desde = now()->subDays($dias - 1)->startOfDay();
        $granularidad = $dias > 90 ? 'mes' : 'dia';

        return [
            'serieTemporal' => $this->serieTemporal($propIds, $desde, $granularidad),
            'porEstado' => $this->porEstado($propIds),
            'topPropiedades' => $this->topPropiedades($propIds, $desde),
        ];
    }

    /** Visitas, favoritos y contactos por día o por mes (rellena los períodos sin datos con 0). */
    private function serieTemporal($propIds, CarbonInterface $desde, string $granularidad): array
    {
        $vistas = $this->conteoPorPeriodo('propiedad_vistas', 'created_at', $propIds, $desde, $granularidad);
        $favoritos = $this->conteoPorPeriodo('favorito', 'fecha_agregado', $propIds, $desde, $granularidad);
        $contactos = $this->conteoPorPeriodo('consultas', 'created_at', $propIds, $desde, $granularidad);

        $esMes = $granularidad === 'mes';
        $intervalo = $esMes ? '1 month' : '1 day';
        $formatoClave = $esMes ? 'Y-m' : 'Y-m-d';
        $formatoLabel = $esMes ? 'M Y' : 'd/m';
        $inicio = $esMes ? $desde->copy()->startOfMonth() : $desde->copy();

        $labels = $vis = $fav = $con = [];

        foreach (CarbonPeriod::create($inicio, $intervalo, now()) as $fecha) {
            $clave = $fecha->format($formatoClave);
            $labels[] = $fecha->format($formatoLabel);
            $vis[] = (int) ($vistas[$clave] ?? 0);
            $fav[] = (int) ($favoritos[$clave] ?? 0);
            $con[] = (int) ($contactos[$clave] ?? 0);
        }

        return [
            'labels' => $labels,
            'series' => [
                ['name' => 'Visitas', 'data' => $vis],
                ['name' => 'Favoritos', 'data' => $fav],
                ['name' => 'Contactos', 'data' => $con],
            ],
        ];
    }

    private function conteoPorPeriodo(string $tabla, string $columna, $propIds, CarbonInterface $desde, string $granularidad): array
    {
        $formato = $granularidad === 'mes' ? '%Y-%m' : '%Y-%m-%d';

        return DB::table($tabla)
            ->selectRaw("DATE_FORMAT($columna, '$formato') as periodo, COUNT(*) as total")
            ->whereIn('propiedad_id', $propIds)
            ->where($columna, '>=', $desde)
            ->groupBy('periodo')
            ->pluck('total', 'periodo')
            ->all();
    }

    private function porEstado($propIds): array
    {
        $datos = Propiedad::whereIn('id', $propIds)
            ->selectRaw('estado_propiedad, COUNT(*) as total')
            ->groupBy('estado_propiedad')
            ->pluck('total', 'estado_propiedad');

        return [
            'labels' => $datos->keys()->all(),
            'series' => $datos->values()->map(fn($v) => (int) $v)->all(),
        ];
    }

    private function topPropiedades($propIds, CarbonInterface $desde, int $limite = 5): array
    {
        return Propiedad::whereIn('id', $propIds)
            ->withCount([
                'vistas as visitas_count' => fn($q) => $q->where('propiedad_vistas.created_at', '>=', $desde),
                'favoritos as favoritos_count' => fn($q) => $q->where('favorito.fecha_agregado', '>=', $desde),
                'consultas as contactos_count' => fn($q) => $q->where('consultas.created_at', '>=', $desde),
            ])
            ->orderByDesc('visitas_count')
            ->take($limite)
            ->get(['id', 'titulo'])
            ->map(fn($p) => [
                'id' => $p->id,
                'titulo' => $p->titulo,
                'visitas' => $p->visitas_count,
                'favoritos' => $p->favoritos_count,
                'contactos' => $p->contactos_count,
            ])
            ->all();
    }
}