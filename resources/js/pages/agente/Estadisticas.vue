<template>
    <PanelLayout :nav-links="navLinks">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-foreground">Estadísticas</h1>
                <p class="mt-1 text-sm text-muted-foreground">Rendimiento de tus publicaciones</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <!-- Selector de propiedad -->
                <div class="relative flex flex-col gap-1">
                    <label for="filtro-propiedad" class="text-xs font-medium text-muted-foreground">Propiedad</label>
                    <input
                        id="filtro-propiedad"
                        type="text"
                        v-model="busqueda"
                        @focus="abierto = true"
                        @blur="cerrarConDelay"
                        placeholder="Buscar propiedad..."
                        autocomplete="off"
                        class="w-80 rounded-lg border border-border bg-card px-3 py-2 text-sm text-foreground"
                    />
                    <ul
                        v-if="abierto && propiedadesFiltradas.length"
                        class="absolute top-full z-20 mt-1 max-h-60 w-80 overflow-auto rounded-lg border border-border bg-card shadow-lg"
                    >
                        <li
                            @mousedown.prevent="seleccionar(null)"
                            class="cursor-pointer px-3 py-2 text-sm text-muted-foreground hover:bg-muted"
                        >
                            Todas las propiedades
                        </li>
                        <li
                            v-for="p in propiedadesFiltradas"
                            :key="p.id"
                            @mousedown.prevent="seleccionar(p)"
                            class="cursor-pointer px-3 py-2 text-sm text-foreground hover:bg-muted"
                        >
                            {{ p.titulo }}
                        </li>
                    </ul>
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
import { ref, computed, watch } from 'vue'
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

// --- Buscador de propiedad en tiempo real (filtra la lista ya cargada) ---
const busqueda = ref('')
const abierto = ref(false)

// Mantener el texto del input sincronizado con la propiedad seleccionada
const seleccionada = computed(() => props.propiedades.find((p) => p.id === props.propiedadId))
watch(() => props.propiedadId, () => {
    busqueda.value = seleccionada.value?.titulo ?? ''
}, { immediate: true })

const propiedadesFiltradas = computed(() => {
    const q = busqueda.value.trim().toLowerCase()
    if (!q) return props.propiedades
    return props.propiedades.filter((p) => p.titulo.toLowerCase().includes(q))
})

function seleccionar(p) {
    abierto.value = false
    busqueda.value = p?.titulo ?? ''
    visitar({ dias: props.dias, propiedad_id: p?.id ?? undefined })
}

function cerrarConDelay() {
    setTimeout(() => (abierto.value = false), 100)
}
</script>