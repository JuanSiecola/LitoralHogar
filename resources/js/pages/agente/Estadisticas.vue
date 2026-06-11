<template>
    <PanelLayout :nav-links="navLinks">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-foreground">Estadísticas</h1>
                <p class="mt-1 text-sm text-muted-foreground">Rendimiento de tus publicaciones</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <!-- Selector de propiedad -->
                <div class="flex flex-col gap-1">
                    <label for="filtro-propiedad" class="text-xs font-medium text-muted-foreground">Propiedad</label>
                    <select
                        id="filtro-propiedad"
                        :value="propiedadId ?? ''"
                        @change="cambiarPropiedad"
                        class="rounded-lg border border-border bg-card px-3 py-2 text-sm text-foreground"
                    >
                        <option value="">Todas las propiedades</option>
                        <option v-for="p in propiedades" :key="p.id" :value="p.id">{{ p.titulo }}</option>
                    </select>
                </div>

                <!-- Botonera de período -->
                <div class="inline-flex rounded-lg border border-border bg-card p-1">
                    <button
                        v-for="o in opciones"
                        :key="o.value"
                        type="button"
                        @click="cambiarPeriodo(o.value)"
                        :class="[
                            'rounded-md px-3 py-1.5 text-sm font-medium transition',
                            dias === o.value
                                ? 'bg-primary text-primary-foreground'
                                : 'text-muted-foreground hover:text-foreground',
                        ]"
                    >
                        {{ o.label }}
                    </button>
                </div>
            </div>
        </div>

        <EstadisticasPanel
            :key="`${dias}-${propiedadId}`"
            :estadisticas="estadisticas"
            :propiedad-seleccionada="propiedadId !== null"
        />
    </PanelLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import PanelLayout from '@/layouts/PanelLayout.vue'
import EstadisticasPanel from '@/components/estadisticas/EstadisticasPanel.vue'
import { useAgenteNav } from '@/composables/useAgenteNav'

const props = defineProps({
    estadisticas: { type: Object, required: true },
    dias:         { type: Number, default: 30 },
    propiedades:  { type: Array,  default: () => [] },
    propiedadId:  { type: Number, default: null },
})
const navLinks = useAgenteNav()

const opciones = [
    { label: '30 días',    value: 30 },
    { label: '3 meses',    value: 90 },
    { label: '6 meses',    value: 180 },
    { label: 'Último año', value: 365 },
]

// Helper único: navega manteniendo los dos filtros
function visitar(params) {
    router.get('/agente/estadisticas', params, {
        preserveScroll: true,
        replace: true,
    })
}

function cambiarPeriodo(dias) {
    visitar({ dias, propiedad_id: props.propiedadId ?? undefined })
}

function cambiarPropiedad(event) {
    visitar({ dias: props.dias, propiedad_id: event.target.value || undefined })
}
</script>