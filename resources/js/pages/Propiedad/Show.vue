<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, useForm, router, usePage } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { formatPrecio } from '@/lib/currency';
import {
    Bed, ShowerHead, Square, Car, Layers, CalendarDays,
    Hammer, PawPrint, MapPin, ChevronLeft, ChevronRight,
    Mail, MessageCircle, User, Banknote, Landmark, Heart,
    Router
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
    es_favorito: boolean;
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

const page = usePage();

const esCliente = computed(() =>
    (page.props.auth as any)?.user?.rol_usuario?.some(
        (r: any) => r.nombre?.toLowerCase() === 'cliente'
    ) ?? false
);

const estaAutenticado = computed(() => !!(page.props.auth as any)?.user);


const esFavorito = ref(props.propiedad.es_favorito);
const guardandoFavorito = ref(false);

 function toggleFavorito() {
      if (guardandoFavorito.value) return;
      guardandoFavorito.value = true;

      const url = `/cliente/favoritos/${props.propiedad.id}`;

      const opciones = {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => {
              esFavorito.value = !esFavorito.value;
              toast.success(esFavorito.value ? 'Agregado a favoritos' : 'Quitado de favoritos');
          },
          onError: () => toast.error('No se pudo actualizar el favorito.'),
          onFinish: () => { guardandoFavorito.value = false; },
      };

      if (esFavorito.value) {
          router.delete(url, opciones);
      } else {
          router.post(url, {}, opciones);
      }
  }

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

const mensajeContacto = computed(() => {
    const ubicacion = [props.propiedad.direccion, props.propiedad.localidad, props.propiedad.departamento]
        .filter(Boolean)
        .join(', ');
    const url = typeof window !== 'undefined' ? window.location.href : '';

    return [
        `Hola, me interesa la propiedad "${props.propiedad.titulo}" publicada en Litoral Hogar.`,
        `Operacion: ${props.propiedad.tipo_operacion}`,
        `Tipo: ${props.propiedad.tipo_propiedad}`,
        ubicacion ? `Ubicacion: ${ubicacion}` : '',
        url ? `Link: ${url}` : '',
        '',
        'Quisiera recibir mas informacion y coordinar una consulta. Gracias.',
    ].filter((line) => line !== '').join('\n');
});

const emailHref = computed(() => {
    const email = props.propiedad.contacto?.email;
    const subject = `Consulta por ${props.propiedad.titulo}`;

    return `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(mensajeContacto.value)}`;
});

const whatsappHref = computed(() => {
    const phone = props.propiedad.contacto?.phone;
    if (!phone) return '';

    let digits = phone.replace(/\D/g, '');

    if (digits.startsWith('00')) {
        digits = digits.slice(2);
    }

    if (digits.startsWith('0')) {
        digits = `598${digits.replace(/^0+/, '')}`;
    } else if (!digits.startsWith('598') && digits.length <= 9) {
        digits = `598${digits}`;
    }

    return `https://wa.me/${digits}?text=${encodeURIComponent(mensajeContacto.value)}`;
});

// Formulario consulta
const form = useForm({
    propiedad_id: props.propiedad.id,
    mensaje: '',
});
const enviando = ref(false);
function enviarConsulta() {
    enviando.value = true;
    form.post('/consultas', {
        onSuccess: () => {
            form.reset('mensaje');
            mostrarDialogConsulta.value = true;
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

// Dialog confirmación consulta
const mostrarDialogConsulta = ref(false);

// Comparador
const mostrarComparador = ref(false);
const linkComparar = ref('');
const errorLink = ref('');

function irAComparar() {
    const match = linkComparar.value.match(/propiedades\/(\d+)/);
    if (!match) {
        errorLink.value = 'Link inválido. Pegá la URL de otra propiedad.';
        return;
    }
    errorLink.value = '';
    const otroId = match[1];
    router.visit(`/propiedades/comparar?ids[]=${props.propiedad.id}&ids[]=${otroId}`);
}
</script>

<template>

    <div class="mx-auto max-w-7xl px-4 py-8">

        <!-- Breadcrumb + Volver -->
        <div class="mb-6 flex items-center justify-between">
            <nav class="flex items-center gap-2 text-sm text-muted-foreground">
                <Link href="/" class="hover:text-foreground">Inicio</Link>
                <span>/</span>
                <Link href="/propiedades" class="hover:text-foreground">Propiedades</Link>
                <span>/</span>
                <span class="text-foreground">{{ propiedad.titulo }}</span>
            </nav>
            <button @click="() => router.visit('/')"
                class="flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2 text-sm font-medium text-foreground transition hover:border-primary/40 hover:bg-primary/5 hover:text-primary">
                <ChevronLeft class="h-4 w-4" />
                Volver
            </button>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

            <!-- Columna principal -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Carousel de imágenes -->
                <div class="relative aspect-video overflow-hidden rounded-2xl bg-muted">
                    <template v-if="imagenes.length > 0">
                        <img :src="imagenes[imagenActual].url" :alt="propiedad.titulo"
                            class="h-full w-full object-cover" />
                        <template v-if="imagenes.length > 1">
                            <button @click="anterior"
                                class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white hover:bg-black/70 transition">
                                <ChevronLeft class="h-5 w-5" />
                            </button>
                            <button @click="siguiente"
                                class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white hover:bg-black/70 transition">
                                <ChevronRight class="h-5 w-5" />
                            </button>
                            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                                <button v-for="(_, i) in imagenes" :key="i" @click="imagenActual = i"
                                    class="h-2 w-2 rounded-full transition"
                                    :class="i === imagenActual ? 'bg-white' : 'bg-white/40'" />
                            </div>
                        </template>
                    </template>
                    <div v-else class="flex h-full w-full items-center justify-center text-muted-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 9.75L12 3l9 6.75V21H3V9.75z" />
                        </svg>
                    </div>

                    <!-- Badge operación -->
                    <span
                        class="absolute top-4 left-4 rounded-full px-3 py-1 text-sm font-semibold text-primary-foreground"
                        :class="propiedad.tipo_operacion === 'Venta' ? 'bg-primary' : 'bg-secondary'">
                        {{ propiedad.tipo_operacion }}
                    </span>
                </div>

                <!-- Título y ubicación -->
                <div>
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <h1 class="text-2xl font-bold text-foreground">{{ propiedad.titulo }}</h1>
                        <div class="flex items-center gap-3">
                            <button v-if="esCliente" @click="toggleFavorito" :disabled="guardandoFavorito"
                                :title="esFavorito ? 'Quitar de favoritos' : 'Agregar a favoritos'" class="flex items-center gap-2 rounded-xl border border-border bg-card px-3 py-2 text-sm font-medium
  transition hover:border-red-300 hover:bg-red-50 disabled:opacity-60"
                                :class="esFavorito ? 'text-red-500' : 'text-foreground'">
                                <Heart class="h-5 w-5" :class="esFavorito ? 'fill-current' : ''" />
                                <span class="hidden sm:inline">{{ esFavorito ? 'Favorito' : 'Favorito' }}</span>
                            </button>
                            <p class="text-2xl font-bold text-primary">{{ precioFormateado }}</p>
                        </div>
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
                        <div class="flex items-center justify-center gap-3 text-sm">
                            <Layers class="h-5 w-5 shrink-0 text-muted-foreground" />
                            <span class="text-muted-foreground">Pisos:</span>
                            <span class="font-medium text-foreground">{{ propiedad.pisos }}</span>
                        </div>
                        <div class="flex items-center justify-center gap-3 text-sm">
                            <CalendarDays class="h-5 w-5 shrink-0 text-muted-foreground" />
                            <span class="text-muted-foreground">Año de construcción:</span>
                            <span class="font-medium text-foreground">{{ propiedad.anio_construccion }}</span>
                        </div>
                        <div class="flex items-center justify-center gap-3 text-sm">
                            <Hammer class="h-5 w-5 shrink-0 text-muted-foreground" />
                            <span class="text-muted-foreground">Estado:</span>
                            <span class="font-medium text-foreground">{{ propiedad.estado_construccion }}</span>
                        </div>
                        <div class="flex items-center justify-center gap-3 text-sm">
                            <PawPrint class="h-5 w-5 shrink-0 text-muted-foreground" />
                            <span class="text-muted-foreground">Mascotas:</span>
                            <span class="font-medium text-foreground">{{ propiedad.acepta_mascotas ? 'Acepta' : 'No acepta' }}</span>
                        </div>
                        <template v-if="propiedad.tipo_operacion === 'Alquiler'">
                            <div v-if="propiedad.expensas" class="flex items-center justify-center gap-3 text-sm">
                                <Banknote class="h-5 w-5 shrink-0 text-muted-foreground" />
                                <span class="text-muted-foreground">Expensas:</span>
                                <span class="font-medium text-foreground">$ {{ propiedad.expensas }}</span>
                            </div>
                            <div v-if="propiedad.deposito" class="flex items-center justify-center gap-3 text-sm">
                                <Landmark class="h-5 w-5 shrink-0 text-muted-foreground" />
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
                        <span v-for="amenidad in propiedad.amenidades" :key="amenidad"
                            class="rounded-full bg-primary/10 px-3 py-1 text-sm font-medium text-primary">
                            {{ amenidad }}
                        </span>
                    </div>
                </div>

                <!-- Comparar con otra propiedad -->
                <div class="rounded-xl border border-border bg-card p-6">
                    <h2 class="mb-3 text-lg font-semibold text-foreground">Comparar</h2>
                    <button @click="mostrarComparador = !mostrarComparador"
                        class="w-full rounded-xl border border-primary px-4 py-3 text-sm font-semibold text-primary transition hover:bg-primary hover:text-primary-foreground">
                        {{ mostrarComparador ? 'Cancelar' : 'Comparar con otra publicación' }}
                    </button>

                    <div v-if="mostrarComparador" class="mt-4 space-y-3">
                        <input v-model="linkComparar" placeholder="Pegá el link de la otra propiedad..."
                            class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring" />
                        <p v-if="errorLink" class="text-xs text-destructive">{{ errorLink }}</p>
                        <button @click="irAComparar"
                            class="w-full rounded-xl bg-primary py-3 text-sm font-semibold text-primary-foreground transition hover:bg-primary/90">
                            Comparar
                        </button>
                    </div>
                </div>

                <!-- Mapa -->
                <div v-if="propiedad.latitud && propiedad.longitud"
                    class="rounded-xl border border-border bg-card overflow-hidden">
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
                    <div class="space-y-3">
                        <a :href="emailHref"
                            class="flex items-center gap-3 rounded-xl border border-border bg-background px-4 py-3 text-sm font-medium text-foreground transition hover:border-primary/40 hover:bg-primary/5 hover:text-primary"
                            title="Enviar correo">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                                <Mail class="h-4 w-4" />
                            </span>
                            <span class="min-w-0">
                                <span class="block text-xs text-muted-foreground">Correo</span>
                                <span class="block truncate">{{ propiedad.contacto.email }}</span>
                            </span>
                        </a>
                        <a v-if="propiedad.contacto.phone" :href="whatsappHref" target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800 transition hover:border-green-400 hover:bg-green-100"
                            title="Enviar mensaje por WhatsApp">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-600 text-white">
                                <MessageCircle class="h-4 w-4" />
                            </span>
                            <span class="min-w-0">
                                <span class="block text-xs text-green-700/80">WhatsApp</span>
                                <span class="block truncate">{{ propiedad.contacto.phone }}</span>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Formulario consulta -->
                <div class="rounded-xl border border-border bg-card p-6">
                    <h2 class="mb-4 text-lg font-semibold text-foreground">Enviá una consulta</h2>
 
                    <form v-if="estaAutenticado" @submit.prevent="enviarConsulta" class="space-y-4">
                        <div>
                            <textarea v-model="form.mensaje" rows="4" placeholder="¿En qué podemos ayudarte?" required
                                class="w-full resize-none rounded-xl border border-border bg-background px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring" />
                            <p v-if="form.errors.mensaje" class="mt-1 text-xs text-destructive">{{ form.errors.mensaje
                            }}</p>
                        </div>
                        <button type="submit" :disabled="enviando"
                            class="w-full rounded-xl bg-primary py-3 text-sm font-semibold text-primary-foreground transition hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-60">
                            {{ enviando ? 'Enviando...' : 'Enviar consulta' }}
                        </button>
                    </form>
 
                    <div v-else class="text-sm text-muted-foreground">
                        Necesitás
                        <Link href="/login" class="font-medium text-primary hover:underline">iniciar sesión</Link>
                        para enviar una consulta sobre esta propiedad.
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Dialog confirmación consulta enviada -->
    <Transition name="dialog-fade">
        <div v-if="mostrarDialogConsulta" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="mostrarDialogConsulta = false" />
            <!-- Panel -->
            <div
                class="relative z-10 w-full max-w-sm rounded-2xl border border-border bg-card p-8 text-center shadow-xl">
                <!-- Ícono -->
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                    <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-foreground">¡Consulta enviada!</h3>
                <p class="mb-6 text-sm text-muted-foreground">
                    Tu mensaje fue enviado correctamente. Nos pondremos en contacto con vos a la brevedad.
                </p>
                <button @click="mostrarDialogConsulta = false"
                    class="w-full rounded-xl bg-primary py-3 text-sm font-semibold text-primary-foreground transition hover:bg-primary/90">
                    Aceptar
                </button>
            </div>
        </div>
    </Transition>
</template>