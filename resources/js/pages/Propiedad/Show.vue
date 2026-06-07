<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { formatPrecio } from '@/lib/currency';
import {
    Bed, ShowerHead, Square, Car, Layers, CalendarDays,
    Hammer, PawPrint, MapPin, ChevronLeft, ChevronRight,
    Mail, Phone, User,
} from 'lucide-vue-next';
import { onMounted, onUnmounted } from 'vue';
interface Imagen {
    id: number;
    url: string;
    es_principal: boolean;
}

interface Propiedad {
    id: number;
    titulo: string;
    tipo_operacion: 'Venta' | 'Alquiler';
    tipo_propiedad: string;
    estado_propiedad: string;
    precio: number;
    nro_habitaciones: number;
    nro_banios: number;
    nro_garage: number;
    superficie_total: number;
    pisos: number;
    anio_construccion: number;
    estado_construccion: string;
    deposito: number | null;
    cant_meses_deposito: number | null;
    expensas: number | null;
    acepta_mascotas: boolean;
    direccion: string;
    localidad: string;
    departamento: string;
    latitud: number | null;
    longitud: number | null;
    imagenes: Imagen[];
    amenidades: string[];
    contacto: {
        tipo: string;
        nombre: string;
        email: string;
        phone: string | null;
    };
}

const props = defineProps<{ propiedad: Propiedad }>();

// Carousel
const imagenActual = ref(0);
const imagenes = computed(() =>
    props.propiedad.imagenes.length > 0 ? props.propiedad.imagenes : []
);
function anterior() {
    imagenActual.value = (imagenActual.value - 1 + imagenes.value.length) % imagenes.value.length;
}
function siguiente() {
    imagenActual.value = (imagenActual.value + 1) % imagenes.value.length;
}

// Precio
const precioFormateado = computed(() =>
    formatPrecio(props.propiedad.precio, props.propiedad.tipo_operacion)
);

// Formulario consulta
const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: '',
});
const enviando = ref(false);
function enviarConsulta() {
    enviando.value = true;
    form.post('/contact', {
        onSuccess: () => {
            toast.success('¡Consulta enviada! Te contactaremos pronto.');
            form.reset();
        },
        onError: () => toast.error('Hubo un error al enviar.'),
        onFinish: () => { enviando.value = false; },
    });
}

// Mapa
let mapInstance: any = null;

onMounted(async () => {
    if (!props.propiedad.latitud || !props.propiedad.longitud) return;

    const L = (await import('leaflet')).default;
    await import('leaflet/dist/leaflet.css');

    // Fix iconos en Vite
    delete (L.Icon.Default.prototype as any)._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    });

    mapInstance = L.map('mapa-propiedad').setView(
        [Number(props.propiedad.latitud), Number(props.propiedad.longitud)],
        15
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
    }).addTo(mapInstance);

    L.marker([Number(props.propiedad.latitud), Number(props.propiedad.longitud)])
        .addTo(mapInstance)
        .bindPopup(props.propiedad.titulo)
        .openPopup();
});

onUnmounted(() => {
    mapInstance?.remove();
});
</script>

<template>

    <div class="mx-auto max-w-7xl px-4 py-8">

        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center gap-2 text-sm text-muted-foreground">
            <Link href="/" class="hover:text-foreground">Inicio</Link>
            <span>/</span>
            <Link href="/propiedades" class="hover:text-foreground">Propiedades</Link>
            <span>/</span>
            <span class="text-foreground">{{ propiedad.titulo }}</span>
        </nav>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

            <!-- Columna principal -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Carousel de imágenes -->
                <div class="relative aspect-video overflow-hidden rounded-2xl bg-muted">
                    <template v-if="imagenes.length > 0">
                        <img
                            :src="imagenes[imagenActual].url"
                            :alt="propiedad.titulo"
                            class="h-full w-full object-cover"
                        />
                        <template v-if="imagenes.length > 1">
                            <button
                                @click="anterior"
                                class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white hover:bg-black/70 transition"
                            >
                                <ChevronLeft class="h-5 w-5" />
                            </button>
                            <button
                                @click="siguiente"
                                class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white hover:bg-black/70 transition"
                            >
                                <ChevronRight class="h-5 w-5" />
                            </button>
                            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                                <button
                                    v-for="(_, i) in imagenes"
                                    :key="i"
                                    @click="imagenActual = i"
                                    class="h-2 w-2 rounded-full transition"
                                    :class="i === imagenActual ? 'bg-white' : 'bg-white/40'"
                                />
                            </div>
                        </template>
                    </template>
                    <div v-else class="flex h-full w-full items-center justify-center text-muted-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75L12 3l9 6.75V21H3V9.75z" />
                        </svg>
                    </div>

                    <!-- Badge operación -->
                    <span
                        class="absolute top-4 left-4 rounded-full px-3 py-1 text-sm font-semibold text-primary-foreground"
                        :class="propiedad.tipo_operacion === 'Venta' ? 'bg-primary' : 'bg-secondary'"
                    >
                        {{ propiedad.tipo_operacion }}
                    </span>
                </div>

                <!-- Título y ubicación -->
                <div>
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <h1 class="text-2xl font-bold text-foreground">{{ propiedad.titulo }}</h1>
                        <p class="text-2xl font-bold text-primary">{{ precioFormateado }}</p>
                    </div>
                    <p class="mt-2 flex items-center gap-1 text-muted-foreground">
                        <MapPin class="h-4 w-4" />
                        {{ propiedad.direccion }}, {{ propiedad.localidad }}, {{ propiedad.departamento }}
                    </p>
                    <div class="mt-2 flex gap-2">
                        <span class="rounded-full bg-muted px-3 py-1 text-xs font-medium text-foreground">
                            {{ propiedad.tipo_propiedad }}
                        </span>
                        <span class="rounded-full bg-muted px-3 py-1 text-xs font-medium text-foreground">
                            {{ propiedad.estado_construccion }}
                        </span>
                    </div>
                </div>

                <!-- Características principales -->
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="flex flex-col items-center gap-1 rounded-xl border border-border bg-card p-4">
                        <Bed class="h-6 w-6 text-primary" />
                        <span class="text-lg font-bold text-foreground">{{ propiedad.nro_habitaciones }}</span>
                        <span class="text-xs text-muted-foreground">Habitaciones</span>
                    </div>
                    <div class="flex flex-col items-center gap-1 rounded-xl border border-border bg-card p-4">
                        <ShowerHead class="h-6 w-6 text-secondary" />
                        <span class="text-lg font-bold text-foreground">{{ propiedad.nro_banios }}</span>
                        <span class="text-xs text-muted-foreground">Baños</span>
                    </div>
                    <div class="flex flex-col items-center gap-1 rounded-xl border border-border bg-card p-4">
                        <Square class="h-6 w-6 text-accent" />
                        <span class="text-lg font-bold text-foreground">{{ propiedad.superficie_total }} m²</span>
                        <span class="text-xs text-muted-foreground">Superficie</span>
                    </div>
                    <div class="flex flex-col items-center gap-1 rounded-xl border border-border bg-card p-4">
                        <Car class="h-6 w-6 text-primary" />
                        <span class="text-lg font-bold text-foreground">{{ propiedad.nro_garage }}</span>
                        <span class="text-xs text-muted-foreground">Garage</span>
                    </div>
                </div>

                <!-- Detalles adicionales -->
                <div class="rounded-xl border border-border bg-card p-6">
                    <h2 class="mb-4 text-lg font-semibold text-foreground">Detalles de la propiedad</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="flex items-center gap-3 text-sm">
                            <Layers class="h-5 w-5 text-muted-foreground" />
                            <span class="text-muted-foreground">Pisos:</span>
                            <span class="font-medium text-foreground">{{ propiedad.pisos }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <CalendarDays class="h-5 w-5 text-muted-foreground" />
                            <span class="text-muted-foreground">Año de construcción:</span>
                            <span class="font-medium text-foreground">{{ propiedad.anio_construccion }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <Hammer class="h-5 w-5 text-muted-foreground" />
                            <span class="text-muted-foreground">Estado:</span>
                            <span class="font-medium text-foreground">{{ propiedad.estado_construccion }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <PawPrint class="h-5 w-5 text-muted-foreground" />
                            <span class="text-muted-foreground">Mascotas:</span>
                            <span class="font-medium text-foreground">{{ propiedad.acepta_mascotas ? 'Acepta' : 'No acepta' }}</span>
                        </div>
                        <template v-if="propiedad.tipo_operacion === 'Alquiler'">
                            <div v-if="propiedad.expensas" class="flex items-center gap-3 text-sm">
                                <span class="text-muted-foreground">Expensas:</span>
                                <span class="font-medium text-foreground">$ {{ propiedad.expensas }}</span>
                            </div>
                            <div v-if="propiedad.deposito" class="flex items-center gap-3 text-sm">
                                <span class="text-muted-foreground">Depósito:</span>
                                <span class="font-medium text-foreground">
                                    $ {{ propiedad.deposito }}
                                    <span v-if="propiedad.cant_meses_deposito" class="text-muted-foreground">
                                        ({{ propiedad.cant_meses_deposito }} meses)
                                    </span>
                                </span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Amenidades -->
                <div v-if="propiedad.amenidades.length > 0" class="rounded-xl border border-border bg-card p-6">
                    <h2 class="mb-4 text-lg font-semibold text-foreground">Amenidades</h2>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="amenidad in propiedad.amenidades"
                            :key="amenidad"
                            class="rounded-full bg-primary/10 px-3 py-1 text-sm font-medium text-primary"
                        >
                            {{ amenidad }}
                        </span>
                    </div>
                </div>
                <!-- Mapa -->
                <div
                    v-if="propiedad.latitud && propiedad.longitud"
                    class="rounded-xl border border-border bg-card overflow-hidden"
                >
                <div class="p-6 pb-3">
                    <h2 class="text-lg font-semibold text-foreground">Ubicación</h2>
                    <p class="text-sm text-muted-foreground mt-1">
                        {{ propiedad.direccion }}, {{ propiedad.localidad }}, {{ propiedad.departamento }}
                    </p>
                </div>
                <div id="mapa-propiedad" class="h-72 w-full z-0" />
            </div>

            </div>

            <!-- Columna lateral -->
            <div class="space-y-5">

                <!-- Tarjeta contacto -->
                <div v-if="propiedad.contacto?.nombre" class="rounded-xl border border-border bg-card p-6">
                    <h2 class="mb-4 text-lg font-semibold text-foreground">Contacto</h2>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                            <User class="h-6 w-6 text-primary" />
                        </div>
                        <div>
                            <p class="font-semibold text-foreground">{{ propiedad.contacto.nombre }}</p>
                            <p class="text-xs capitalize text-muted-foreground">{{ propiedad.contacto.tipo }}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <a
                            :href="`mailto:${propiedad.contacto.email}`"
                            class="flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground transition"
                        >
                            <Mail class="h-4 w-4" />
                            {{ propiedad.contacto.email }}
                        </a>
                        <a
                            v-if="propiedad.contacto.phone"
                            :href="`tel:${propiedad.contacto.phone}`"
                            class="flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground transition"
                        >
                            <Phone class="h-4 w-4" />
                            {{ propiedad.contacto.phone }}
                        </a>
                    </div>
                </div>

                <!-- Formulario consulta -->
                <div class="rounded-xl border border-border bg-card p-6">
                    <h2 class="mb-4 text-lg font-semibold text-foreground">Enviá una consulta</h2>
                    <form @submit.prevent="enviarConsulta" class="space-y-4">
                        <div>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Tu nombre"
                                required
                                class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                            />
                            <p v-if="form.errors.name" class="mt-1 text-xs text-destructive">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="Tu email"
                                required
                                class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                            />
                            <p v-if="form.errors.email" class="mt-1 text-xs text-destructive">{{ form.errors.email }}</p>
                        </div>
                        <div>
                            <input
                                v-model="form.phone"
                                type="tel"
                                placeholder="Teléfono / WhatsApp"
                                class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                            />
                        </div>
                        <div>
                            <textarea
                                v-model="form.message"
                                rows="4"
                                placeholder="¿En qué podemos ayudarte?"
                                required
                                class="w-full resize-none rounded-xl border border-border bg-background px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                            />
                            <p v-if="form.errors.message" class="mt-1 text-xs text-destructive">{{ form.errors.message }}</p>
                        </div>
                        <button
                            type="submit"
                            :disabled="enviando"
                            class="w-full rounded-xl bg-primary py-3 text-sm font-semibold text-primary-foreground transition hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            {{ enviando ? 'Enviando...' : 'Enviar consulta' }}
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</template>