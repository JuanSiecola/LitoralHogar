<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PanelLayout from '@/layouts/PanelLayout.vue';
import { useClienteNav } from '@/composables/useClienteNav';
import { usePropiedadesFilters } from '@/composables/usePropiedadesFilters';
import type {
    OpcionUbicacion,
    PaginatedPropiedades,
    PropiedadFilters,
} from '@/types';

import FiltroCaracteristicas from './Propiedades/partials/FiltroCaracteristicas.vue';
import FiltroOperacionTipo from './Propiedades/partials/FiltroOperacionTipo.vue';
import FiltroPrecio from './Propiedades/partials/FiltroPrecio.vue';
import FiltroUbicacion from './Propiedades/partials/FiltroUbicacion.vue';
import FiltrosSidebar from './Propiedades/partials/FiltrosSidebar.vue';
import FiltrosToggle from './Propiedades/partials/FiltrosToggle.vue';
import PropiedadesGrid from './Propiedades/partials/PropiedadesGrid.vue';
import PropiedadesHeader from './Propiedades/partials/PropiedadesHeader.vue';
import PropiedadesPaginacion from './Propiedades/partials/PropiedadesPaginacion.vue';

const props = defineProps<{
    propiedades: PaginatedPropiedades;
    filters: PropiedadFilters;
    departamentos: OpcionUbicacion[];
}>();

const navLinks = useClienteNav();

const {
    filtros,
    precioRango,
    monedaFiltro,
    precioMinFormateado,
    precioMaxFormateado,
    hayFiltrosActivos,
    limpiarFiltros,
    urlConPagina,
    PRECIO_MIN_TOPE,
    PRECIO_MAX_TOPE,
    PRECIO_STEP,
    PER_PAGE_OPCIONES,
} = usePropiedadesFilters(props.filters);

const filtrosAbiertos = ref(false);

const currentPage = computed(() => props.propiedades.current_page);
const lastPage = computed(() => props.propiedades.last_page);
</script>

<template>
    <PanelLayout :nav-links="navLinks">
        <Head title="Propiedades" />

        <section class="w-full">
            <PropiedadesHeader
                :total="propiedades.total"
                :from="propiedades.from"
                :to="propiedades.to"
                :current-page="currentPage"
                :last-page="lastPage"
            />

            <FiltrosToggle
                v-model:abierto="filtrosAbiertos"
                :hay-filtros-activos="hayFiltrosActivos"
                @limpiar="limpiarFiltros"
            />

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-[300px_minmax(0,1fr)]">
                <FiltrosSidebar
                    :class="filtrosAbiertos ? 'block' : 'hidden lg:block'"
                    :hay-filtros-activos="hayFiltrosActivos"
                    @limpiar="limpiarFiltros"
                >
                    <FiltroOperacionTipo
                        v-model:tipo-operacion="filtros.tipo_operacion"
                        v-model:tipo-propiedad="filtros.tipo_propiedad"
                    />
                    <FiltroUbicacion
                        v-model:departamento-id="filtros.departamento_id"
                        v-model:localidad-id="filtros.localidad_id"
                        :departamentos="departamentos"
                    />
                    <FiltroCaracteristicas
                        v-model:nro-habitaciones="filtros.nro_habitaciones"
                        v-model:nro-banios="filtros.nro_banios"
                        v-model:superficie-min="filtros.superficie_min"
                    />
                    <FiltroPrecio
                        v-model:rango="precioRango"
                        :moneda="monedaFiltro"
                        :precio-min-formateado="precioMinFormateado"
                        :precio-max-formateado="precioMaxFormateado"
                        :min="PRECIO_MIN_TOPE"
                        :max="PRECIO_MAX_TOPE"
                        :step="PRECIO_STEP"
                    />
                </FiltrosSidebar>

                <div>
                    <PropiedadesGrid
                        :propiedades="propiedades.data"
                        :hay-filtros-activos="hayFiltrosActivos"
                        @limpiar="limpiarFiltros"
                    />
                    <PropiedadesPaginacion
                        v-model:per-page="filtros.per_page"
                        :current-page="currentPage"
                        :last-page="lastPage"
                        :total="propiedades.total"
                        :url-con-pagina="urlConPagina"
                        :opciones="PER_PAGE_OPCIONES"
                    />
                </div>
            </div>
        </section>
    </PanelLayout>
</template>
