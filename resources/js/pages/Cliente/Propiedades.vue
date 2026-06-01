<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';
import PropertyCard from '@/components/PropertyCard.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Bed, Home, MapPin, ShowerHead, Square } from 'lucide-vue-next';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

defineOptions({ layout: PublicLayout });

interface Propiedad {
    id: number;
    titulo: string;
    tipo_operacion: 'Venta' | 'Alquiler';
    tipo_propiedad: string;
    precio: number;
    nro_habitaciones: number;
    nro_banios: number;
    superficie_total: number;
    localidad: string;
    departamento: string;
    latitud?: number | string | null;
    longitud?: number | string | null;
    imagen_url?: string | null;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedPropiedades {
    data: Propiedad[];
    links: PaginationLink[];
    from: number | null;
    to: number | null;
    total: number;
}

defineProps<{
    propiedades: PaginatedPropiedades;
}>();

const propiedadSeleccionada = ref<Propiedad | null>(null);
const mapaDetalle = ref<HTMLDivElement | null>(null);
let leafletMap: L.Map | null = null;
let leafletMarker: L.Marker | null = null;

delete (L.Icon.Default.prototype as any)._getIconUrl
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

const precioSeleccionado = computed(() => {
    if (!propiedadSeleccionada.value) return '';

    const num = new Intl.NumberFormat('es-UY').format(
        propiedadSeleccionada.value.precio,
    );

    return propiedadSeleccionada.value.tipo_operacion === 'Alquiler'
        ? `USD ${num}/mes`
        : `USD ${num}`;
});

const coordenadasSeleccionadas = computed<[number, number] | null>(() => {
    const propiedad = propiedadSeleccionada.value;
    if (!propiedad) return null;

    const latitud = Number(propiedad.latitud);
    const longitud = Number(propiedad.longitud);

    if (!Number.isFinite(latitud) || !Number.isFinite(longitud)) {
        return null;
    }

    return [latitud, longitud];
});

function paginationLabel(label: string) {
    return label
        .replace('&laquo; Previous', 'Anterior')
        .replace('Next &raquo;', 'Siguiente');
}

function cerrarDetalle(open: boolean) {
    if (!open) {
        propiedadSeleccionada.value = null;
    }
}

function destruirMapa() {
    if (!leafletMap) return;

    leafletMap.remove();
    leafletMap = null;
    leafletMarker = null;
}

async function actualizarMapa() {
    await nextTick();

    const coordenadas = coordenadasSeleccionadas.value;
    if (!mapaDetalle.value || !coordenadas) {
        destruirMapa();
        return;
    }

    if (!leafletMap) {
        leafletMap = L.map(mapaDetalle.value).setView(coordenadas, 15);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(leafletMap);
    } else {
        leafletMap.setView(coordenadas, 15);
    }

    if (leafletMarker) {
        leafletMarker.setLatLng(coordenadas);
    } else {
        leafletMarker = L.marker(coordenadas).addTo(leafletMap);
    }

    leafletMarker.bindPopup(propiedadSeleccionada.value?.titulo ?? 'Propiedad');
    leafletMap.invalidateSize();
    window.setTimeout(() => leafletMap?.invalidateSize(), 250);
}

watch(propiedadSeleccionada, actualizarMapa);

onBeforeUnmount(destruirMapa);
</script>

<template>
    <Head title="Propiedades"/>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Propiedades</h1>
                <p class="mt-2 text-muted-foreground">
                    Explora las propiedades disponibles para comprar o alquilar.
                </p>
            </div>

            <p v-if="propiedades.total > 0" class="text-sm text-muted-foreground">
                Mostrando {{ propiedades.from }}-{{ propiedades.to }} de
                {{ propiedades.total }}
            </p>
        </div>

        <div
            v-if="propiedades.data.length"
            class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
        >
            <PropertyCard
                v-for="propiedad in propiedades.data"
                :key="propiedad.id"
                v-bind="propiedad"
                selectable
                @select="propiedadSeleccionada = $event"
            />
        </div>

        <div
            v-else
            class="rounded-lg border border-border bg-card px-6 py-12 text-center"
        >
            <h2 class="text-lg font-semibold text-foreground">
                No hay propiedades disponibles
            </h2>
        </div>

        <nav
            v-if="propiedades.links.length > 3"
            class="mt-10 flex flex-wrap justify-center gap-2"
            aria-label="Paginacion de propiedades"
        >
            <component
                :is="link.url ? Link : 'span'"
                v-for="link in propiedades.links"
                :key="`${link.label}-${link.url}`"
                :href="link.url || undefined"
                class="min-w-10 rounded-md border px-3 py-2 text-center text-sm font-medium transition-colors"
                :class="[
                    link.active
                        ? 'border-primary bg-primary text-primary-foreground'
                        : 'border-border bg-background text-foreground hover:border-primary hover:text-primary',
                    !link.url && 'cursor-not-allowed opacity-50 hover:border-border hover:text-foreground',
                ]"
            >
                {{ paginationLabel(link.label) }}
            </component>
        </nav>

        <Dialog
            :open="!!propiedadSeleccionada"
            @update:open="cerrarDetalle"
        >
            <DialogContent class="max-h-[90vh] overflow-y-auto p-0 sm:max-w-5xl">
                <div
                    v-if="propiedadSeleccionada"
                    class="grid min-h-[520px] grid-cols-1 md:grid-cols-[1.15fr_0.85fr]"
                >
                    <div class="bg-muted">
                        <img
                            v-if="propiedadSeleccionada.imagen_url"
                            :src="propiedadSeleccionada.imagen_url"
                            :alt="propiedadSeleccionada.titulo"
                            class="h-full min-h-80 w-full object-cover"
                        />
                        <div
                            v-else
                            class="flex h-full min-h-80 w-full items-center justify-center text-muted-foreground"
                        >
                            <Home class="h-20 w-20" />
                        </div>
                    </div>

                    <div class="flex flex-col p-6 md:p-8">
                        <DialogHeader class="space-y-3 text-left">
                            <div class="flex flex-wrap items-center gap-2">
                                <span
                                    class="rounded-full px-3 py-1 text-xs font-semibold text-white"
                                    :class="
                                        propiedadSeleccionada.tipo_operacion === 'Venta'
                                            ? 'bg-blue-600'
                                            : 'bg-emerald-600'
                                    "
                                >
                                    {{ propiedadSeleccionada.tipo_operacion }}
                                </span>
                                <span
                                    class="rounded-full border border-border px-3 py-1 text-xs font-medium text-muted-foreground"
                                >
                                    {{ propiedadSeleccionada.tipo_propiedad }}
                                </span>
                            </div>

                            <DialogTitle class="pr-8 text-2xl font-bold leading-tight text-foreground">
                                {{ propiedadSeleccionada.titulo }}
                            </DialogTitle>

                            <p class="text-3xl font-bold text-primary">
                                {{ precioSeleccionado }}
                            </p>
                        </DialogHeader>

                        <div class="mt-6 flex items-start gap-2 text-sm text-muted-foreground">
                            <MapPin class="mt-0.5 h-4 w-4 shrink-0" />
                            <span>
                                {{ propiedadSeleccionada.localidad }},
                                {{ propiedadSeleccionada.departamento }}
                            </span>
                        </div>

                        <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="rounded-lg border border-border p-4">
                                <Bed class="mb-3 h-5 w-5 text-red-800" />
                                <p class="text-2xl font-semibold text-foreground">
                                    {{ propiedadSeleccionada.nro_habitaciones }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    Habitaciones
                                </p>
                            </div>

                            <div class="rounded-lg border border-border p-4">
                                <ShowerHead class="mb-3 h-5 w-5 text-sky-700" />
                                <p class="text-2xl font-semibold text-foreground">
                                    {{ propiedadSeleccionada.nro_banios }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    Baños
                                </p>
                            </div>

                            <div class="rounded-lg border border-border p-4">
                                <Square class="mb-3 h-5 w-5 text-green-700" />
                                <p class="text-2xl font-semibold text-foreground">
                                    {{ propiedadSeleccionada.superficie_total }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    m² totales
                                </p>
                            </div>
                        </div>

                        <div class="mt-8 border-t border-border pt-6">
                            <h3 class="text-sm font-semibold text-foreground">
                                Información de la propiedad
                            </h3>
                            <dl class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                                <div>
                                    <dt class="text-muted-foreground">Tipo</dt>
                                    <dd class="font-medium text-foreground">
                                        {{ propiedadSeleccionada.tipo_propiedad }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-muted-foreground">Operación</dt>
                                    <dd class="font-medium text-foreground">
                                        {{ propiedadSeleccionada.tipo_operacion }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-muted-foreground">Ciudad</dt>
                                    <dd class="font-medium text-foreground">
                                        {{ propiedadSeleccionada.localidad }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-muted-foreground">Departamento</dt>
                                    <dd class="font-medium text-foreground">
                                        {{ propiedadSeleccionada.departamento }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div class="mt-8 border-t border-border pt-6">
                            <h3 class="text-sm font-semibold text-foreground">
                                Ubicacion
                            </h3>
                            <div
                                v-if="coordenadasSeleccionadas"
                                ref="mapaDetalle"
                                class="mt-4 h-72 w-full overflow-hidden rounded-lg border border-border"
                                aria-label="Mapa de ubicacion de la propiedad"
                            />
                            <p
                                v-else
                                class="mt-4 rounded-lg border border-border bg-muted p-4 text-sm text-muted-foreground"
                            >
                                Esta propiedad no tiene coordenadas cargadas.
                            </p>
                        </div>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    </section>
</template>
