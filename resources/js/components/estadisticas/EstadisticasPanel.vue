<script setup lang="ts">
import { computed } from 'vue'
import EstadisticaCard from '@/components/estadisticas/EstadisticaCard.vue'
import { Eye, Heart, Mail } from 'lucide-vue-next'

interface Serie { name: string; data: number[] }
interface Estadisticas {
    serieTemporal: { labels: string[]; series: Serie[] }
    porEstado: { labels: string[]; series: number[] }
    topPropiedades: { id: number; titulo: string; visitas: number; favoritos: number; contactos: number }[]
}

const props = defineProps<{ estadisticas: Estadisticas; propiedadSeleccionada?: boolean }>()

const palette = ['hsl(197, 52%, 36%)', 'hsl(28, 68%, 64%)', 'hsl(21, 48%, 52%)', 'hsl(72, 16%, 50%)', 'hsl(177, 18%, 42%)']
// --- Líneas ---
const lineaOptions = computed(() => {
    const labels = props.estadisticas.serieTemporal.labels
    return {
        chart: { toolbar: { show: false }, fontFamily: 'inherit' },
        colors: palette,
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: labels,
            tickAmount: Math.min(labels.length, 12),
            labels: {
                rotate: 0,
                hideOverlappingLabels: true,
                style: { colors: '#6b7280' },
            },
        },
        yaxis: { labels: { style: { colors: '#6b7280' } } },
        legend: { position: 'top', labels: { colors: '#6b7280' } },
        grid: { borderColor: 'rgba(120,120,120,0.15)' },
        dataLabels: { enabled: false },
    }
})
const lineaSeries = computed(() =>
    props.estadisticas.serieTemporal.series.filter((s) => s.name !== 'Contactos')
)
const lineaVacia = computed(() => lineaSeries.value.every((s) => s.data.every((n) => n === 0)))

const estadoOptions = computed(() => ({
    chart: { fontFamily: 'inherit' },
    labels: props.estadisticas.porEstado.labels,
    colors: palette,
    legend: { position: 'bottom', labels: { colors: '#6b7280' } },
    dataLabels: { enabled: true },
    plotOptions: {
        pie: { donut: { labels: { show: true, total: { show: true, label: 'Total', color: '#6b7280' } } } },
    },
}))
const estadoSeries = computed(() => props.estadisticas.porEstado.series)
const estadoVacio = computed(() => estadoSeries.value.reduce((a, b) => a + b, 0) === 0)

</script>

<template>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <EstadisticaCard title="Actividad de las publicaciones" subtitle="Visitas y favoritos por día"
            :vacio="lineaVacia" class="lg:col-span-2">
            <apexchart type="line" height="320" :options="lineaOptions" :series="lineaSeries" />
        </EstadisticaCard>

        <EstadisticaCard title="Propiedades por estado" subtitle="Distribución de tu inventario" :vacio="estadoVacio">
            <apexchart type="donut" height="320" :options="estadoOptions" :series="estadoSeries" />
        </EstadisticaCard>

        <EstadisticaCard title="Propiedades más populares" subtitle="Top 5 por visitas">
            <div v-if="estadisticas.topPropiedades.length" class="divide-y divide-border">
                <div v-for="(p, i) in estadisticas.topPropiedades" :key="p.id" class="flex items-center gap-3 py-3">
                    <span
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-muted text-xs font-semibold text-muted-foreground">{{
                            i + 1 }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xm font-medium text-foreground">{{ p.titulo }}</p>
                        <div class="mt-0.5 flex items-center gap-3 text-xm text-muted-foreground">
                            <span class="inline-flex items-center gap-1">
                                <Eye class="h-3.5 w-3.5 text-primary" /> {{ p.visitas }}
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <Heart class="h-3.5 w-3.5 text-red-500" /> {{ p.favoritos }}
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <Mail class="h-3.5 w-3.5 text-accent" /> {{ p.contactos }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <p v-else class="py-10 text-center text-sm text-muted-foreground">Sin datos todavía.</p>
        </EstadisticaCard>
    </div>
</template>