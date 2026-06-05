<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Bed, ShowerHead, Square } from 'lucide-vue-next';
import { formatPrecio } from '@/lib/currency';

interface Props {
    id: number;
    titulo: string;
    tipo_operacion: 'Venta' | 'Alquiler';
    tipo_propiedad: string;
    precio: number;
    nro_habitaciones: number;
    nro_banios: number;
    superficie_total: number;
    localidad: {
        type: [String, null],
        default: null
    }
    departamento: string;
    latitud?: number | string | null;
    longitud?: number | string | null;
    imagen_url?: string | null;
    selectable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    selectable: false,
});

const emit = defineEmits<{
    select: [propiedad: Omit<Props, 'selectable'>];
}>();

const precioFormateado = computed(() =>
    formatPrecio(props.precio, props.tipo_operacion),
);

function seleccionar() {
    if (!props.selectable) return;
    emit('select', {
        id: props.id,
        titulo: props.titulo,
        tipo_operacion: props.tipo_operacion,
        tipo_propiedad: props.tipo_propiedad,
        precio: props.precio,
        nro_habitaciones: props.nro_habitaciones,
        nro_banios: props.nro_banios,
        superficie_total: props.superficie_total,
        localidad: props.localidad,
        departamento: props.departamento,
        latitud: props.latitud,
        longitud: props.longitud,
        imagen_url: props.imagen_url,
    });
}
</script>

<template>
    <component
        :is="selectable ? 'button' : Link"
        :href="`/propiedades/${id}`"
        :type="selectable ? 'button' : undefined"
        class="group block w-full overflow-hidden rounded-xl border border-border bg-card text-left shadow-card transition-shadow duration-200 hover:shadow-md"
        @click="seleccionar"
    >
        <!-- Imagen -->
        <div class="relative aspect-4/3 overflow-hidden bg-muted">
            <img
                v-if="imagen_url"
                :src="imagen_url"
                :alt="titulo"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
            <div
                v-else
                class="flex h-full w-full items-center justify-center text-muted-foreground"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75L12 3l9 6.75V21H3V9.75z" />
                </svg>
            </div>

            <!-- Badge operación — usa paleta de la marca -->
            <span
                class="absolute top-3 left-3 rounded-full px-2.5 py-1 text-xs font-semibold text-primary-foreground"
                :class="tipo_operacion === 'Venta' ? 'bg-primary' : 'bg-secondary'"
            >
                {{ tipo_operacion }}
            </span>
        </div>

        <!-- Info -->
        <div class="p-4">
            <p class="text-lg font-bold text-primary">{{ precioFormateado }}</p>
            <h3 class="mt-1 line-clamp-2 text-sm font-medium text-foreground">
                {{ titulo }}
            </h3>
            <p class="mt-1 text-xs text-muted-foreground">
                {{ localidad }}, {{ departamento }}
            </p>

            <!-- Datos clave -->
            <div class="mt-3 flex items-center gap-4 border-t border-border pt-3 text-sm text-muted-foreground">
                <span v-if="nro_habitaciones > 0" class="flex items-center gap-1">
                    <Bed class="h-4 w-4 text-primary" />
                    {{ nro_habitaciones }} hab.
                </span>
                <span v-if="nro_banios > 0" class="flex items-center gap-1">
                    <ShowerHead class="h-4 w-4 text-secondary" />
                    {{ nro_banios }} baños
                </span>
                <span class="flex items-center gap-1">
                    <Square class="h-4 w-4 text-accent" />
                    {{ superficie_total }} m²
                </span>
            </div>
        </div>
    </component>
</template>