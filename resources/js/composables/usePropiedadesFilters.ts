import { computed, reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { watchDebounced } from '@vueuse/core';
import { etiquetaMoneda, formatNumero } from '@/lib/currency';
import type { PropiedadFilters } from '@/types';

export const PRECIO_MIN_TOPE = 0;
export const PRECIO_MAX_TOPE = 1_000_000;
export const PRECIO_STEP = 10_000;
export const PER_PAGE_OPCIONES = [12, 24, 48] as const;
export const PER_PAGE_DEFAULT = '12';
const ENDPOINT = '/cliente/propiedades';

export function usePropiedadesFilters(initialFilters: PropiedadFilters) {
    const filtros = reactive({
        tipo_operacion: initialFilters.tipo_operacion ?? '',
        tipo_propiedad: initialFilters.tipo_propiedad ?? '',
        departamento_id: initialFilters.departamento_id?.toString() ?? '',
        localidad_id: initialFilters.localidad_id?.toString() ?? '',
        nro_habitaciones: initialFilters.nro_habitaciones?.toString() ?? '',
        nro_banios: initialFilters.nro_banios?.toString() ?? '',
        superficie_min: initialFilters.superficie_min?.toString() ?? '',
        per_page: initialFilters.per_page?.toString() ?? PER_PAGE_DEFAULT,
    });

    const precioRango = ref<[number, number]>([
        Number(initialFilters.precio_min) || PRECIO_MIN_TOPE,
        Number(initialFilters.precio_max) || PRECIO_MAX_TOPE,
    ]);

    const monedaFiltro = computed(() => etiquetaMoneda(filtros.tipo_operacion));

    const precioMinFormateado = computed(
        () => `${monedaFiltro.value} ${formatNumero(precioRango.value[0])}`,
    );

    const precioMaxFormateado = computed(() => {
        const max = precioRango.value[1];
        const sufijo = max >= PRECIO_MAX_TOPE ? '+' : '';
        return `${monedaFiltro.value} ${formatNumero(max)}${sufijo}`;
    });

    const hayFiltrosActivos = computed(
        () =>
            filtros.tipo_operacion !== ''
            || filtros.tipo_propiedad !== ''
            || filtros.departamento_id !== ''
            || filtros.localidad_id !== ''
            || filtros.nro_habitaciones !== ''
            || filtros.nro_banios !== ''
            || filtros.superficie_min !== ''
            || precioRango.value[0] > PRECIO_MIN_TOPE
            || precioRango.value[1] < PRECIO_MAX_TOPE,
    );

    function filtrosLimpios(): Record<string, string> {
        const limpios: Record<string, string> = {};

        if (filtros.tipo_operacion) limpios.tipo_operacion = filtros.tipo_operacion;
        if (filtros.tipo_propiedad) limpios.tipo_propiedad = filtros.tipo_propiedad;
        if (filtros.departamento_id) limpios.departamento_id = filtros.departamento_id;
        if (filtros.localidad_id) limpios.localidad_id = filtros.localidad_id;
        if (filtros.nro_habitaciones) limpios.nro_habitaciones = filtros.nro_habitaciones;
        if (filtros.nro_banios) limpios.nro_banios = filtros.nro_banios;
        if (filtros.superficie_min) limpios.superficie_min = filtros.superficie_min;

        if (precioRango.value[0] > PRECIO_MIN_TOPE) {
            limpios.precio_min = precioRango.value[0].toString();
        }
        if (precioRango.value[1] < PRECIO_MAX_TOPE) {
            limpios.precio_max = precioRango.value[1].toString();
        }

        if (filtros.per_page && filtros.per_page !== PER_PAGE_DEFAULT) {
            limpios.per_page = filtros.per_page;
        }

        return limpios;
    }

    function limpiarFiltros() {
        filtros.tipo_operacion = '';
        filtros.tipo_propiedad = '';
        filtros.departamento_id = '';
        filtros.localidad_id = '';
        filtros.nro_habitaciones = '';
        filtros.nro_banios = '';
        filtros.superficie_min = '';
        filtros.per_page = PER_PAGE_DEFAULT;
        precioRango.value = [PRECIO_MIN_TOPE, PRECIO_MAX_TOPE];
    }

    function urlConPagina(pagina: number): string {
        const params = new URLSearchParams(filtrosLimpios());
        params.set('page', pagina.toString());
        return `${ENDPOINT}?${params.toString()}`;
    }

    watchDebounced(
        [
            () => filtros.tipo_operacion,
            () => filtros.tipo_propiedad,
            () => filtros.departamento_id,
            () => filtros.localidad_id,
            () => filtros.nro_habitaciones,
            () => filtros.nro_banios,
            () => filtros.superficie_min,
            () => filtros.per_page,
            () => precioRango.value[0],
            () => precioRango.value[1],
        ],
        () => {
            router.get(ENDPOINT, filtrosLimpios(), {
                preserveScroll: true,
                preserveState: true,
                replace: true,
                only: ['propiedades', 'filters'],
            });
        },
        { debounce: 400 },
    );

    return {
        filtros,
        precioRango,
        monedaFiltro,
        precioMinFormateado,
        precioMaxFormateado,
        hayFiltrosActivos,
        filtrosLimpios,
        limpiarFiltros,
        urlConPagina,
        PRECIO_MIN_TOPE,
        PRECIO_MAX_TOPE,
        PRECIO_STEP,
        PER_PAGE_OPCIONES,
    };
}
