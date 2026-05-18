<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import PropertyCard from '@/components/PropertyCard.vue';
import LandingNavbar from '@/components/LandingNavbar.vue';
import { useForm } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import {
    Home,
    Building2,
    Trees,
    Store,
    Briefcase,
    Search,
    Mail,
} from 'lucide-vue-next';
import type { Component } from 'vue';

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
    ciudad: string;
    departamento: string;
    imagen_url?: string | null;
}

const props = defineProps<{
    propiedadesDestacadas: Propiedad[];
    categorias: { tipo_propiedad: string; tipo_operacion: string }[];
}>();

const busqueda = ref({
    operacion: '',
    tipo: '',
    ciudad: '',
});

const iconos: Record<string, Component> = {
    Casa: Home,
    Apartamento: Building2,
    Terreno: Trees,
    Local: Store,
    Oficina: Briefcase,
};

function buscar() {
    router.get(
        '/propiedades',
        {
            tipo_operacion: busqueda.value.operacion,
            tipo_propiedad: busqueda.value.tipo,
            ciudad: busqueda.value.ciudad,
        },
        { preserveState: false },
    );
}

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: '',
});

const loading = ref(false);

const submit = () => {
    loading.value = true;

    form.post('/contact', {
        onSuccess: () => {
            toast.success('¡Consulta enviada! Te contactaremos pronto.');
            form.reset();
        },
        onError: () => {
            toast.error('Hubo un error al enviar el formulario.');
        },
        onFinish: () => {
            loading.value = false;
        }
    });
};
</script>

<template>
    <!-- Hero -->
    <section
        class="relative bg-gradient-to-br from-primary/10 to-background px-4 py-20"
    >
        <div class="mx-auto max-w-4xl text-center">
            <h1 class="text-4xl font-bold text-foreground md:text-5xl">
                Encontrá tu próximo hogar para comprar o alquilar en Uruguay
            </h1>
            <p class="mt-4 text-lg text-muted-foreground">
                Miles de propiedades en venta y alquiler en todos los
                departamentos del país.
            </p>

            <!-- Buscador -->
            <div
                class="mt-8 flex flex-col gap-3 rounded-2xl bg-card p-4 shadow-card md:flex-row"
            >
                <select
                    v-model="busqueda.operacion"
                    class="flex-1 rounded-lg border border-border bg-background px-3 py-2 text-sm"
                >
                    <option value="">Operación</option>
                    <option value="Venta">Venta</option>
                    <option value="Alquiler">Alquiler</option>
                </select>
                <select
                    v-model="busqueda.tipo"
                    class="flex-1 rounded-lg border border-border bg-background px-3 py-2 text-sm"
                >
                    <option value="">Tipo</option>
                    <option value="Casa">Casa</option>
                    <option value="Apartamento">Apartamento</option>
                    <option value="Local">Local</option>
                    <option value="Terreno">Terreno</option>
                    <option value="Oficina">Oficina</option>
                </select>
                <input
                    v-model="busqueda.ciudad"
                    type="text"
                    placeholder="Ciudad (ej: Montevideo)"
                    class="flex-1 rounded-lg border border-border bg-background px-3 py-2 text-sm"
                />
                <button
                    @click="buscar"
                    class="rounded-lg bg-primary px-6 py-2 font-semibold text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    Buscar
                </button>
            </div>
        </div>
    </section>

    <!-- Propiedades destacadas -->
    <section id="destacadas" class="mx-auto max-w-7xl px-4 py-16">
        <h2 class="mb-2 text-2xl font-bold text-foreground">
            Propiedades destacadas
        </h2>
        <p class="mb-8 text-muted-foreground">
            Las últimas propiedades disponibles
        </p>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <PropertyCard
                v-for="prop in propiedadesDestacadas"
                :key="prop.id"
                v-bind="prop"
            />
        </div>

        <div class="mt-10 text-center">
            <a
                href="/propiedades"
                class="inline-block rounded-lg border border-primary px-8 py-3 font-semibold text-primary transition-colors hover:bg-primary hover:text-primary-foreground"
            >
                Ver todas las propiedades
            </a>
        </div>
    </section>

    <!-- Cómo funciona -->
    <section class="bg-gray-100 px-4 py-16">
        <div class="mx-auto max-w-5xl text-center">
            <h2 class="mb-2 text-2xl font-bold text-foreground">
                ¿Cómo funciona?
            </h2>
            <p class="mb-12 text-muted-foreground">
                Encontrar tu propiedad ideal es simple
            </p>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-primary/10"
                    >
                        <Search class="h-7 w-7 text-primary" />
                    </div>
                    <h3 class="font-semibold text-foreground">1. Buscá</h3>
                    <p class="text-sm text-muted-foreground">
                        Usá los filtros para encontrar propiedades según tu
                        presupuesto, tipo y ciudad.
                    </p>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-primary/10"
                    >
                        <Mail class="h-7 w-7 text-primary" />
                    </div>
                    <h3 class="font-semibold text-foreground">2. Consultá</h3>
                    <p class="text-sm text-muted-foreground">
                        Enviá una consulta directamente al agente o inmobiliaria
                        desde la ficha de la propiedad.
                    </p>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-primary/10"
                    >
                        <Home class="h-7 w-7 text-primary" />
                    </div>
                    <h3 class="font-semibold text-foreground">3. ¡Listo!</h3>
                    <p class="text-sm text-muted-foreground">
                        El agente se pondrá en contacto para coordinar una
                        visita o responder tus dudas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categorías -->
    <section class="mx-auto max-w-7xl px-4 py-16">
        <h2 class="mb-8 text-2xl font-bold text-foreground">
            Explorá por categoría
        </h2>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
                <a
                    v-for="cat in categorias"
                    :key="`${cat.tipo_propiedad}-${cat.tipo_operacion}`"
                    :href="`/propiedades?tipo_propiedad=${cat.tipo_propiedad}&tipo_operacion=${cat.tipo_operacion}`"
                    class="flex flex-col items-center gap-2 rounded-xl border border-border bg-card p-4 text-center transition-colors hover:border-primary hover:bg-primary/5"
                >
                    <component
                        :is="iconos[cat.tipo_propiedad] ?? Home"
                        class="h-8 w-8 text-primary"
                    />
                    <span class="text-xs font-medium text-foreground">
                        {{ cat.tipo_propiedad }}s en {{ cat.tipo_operacion }}
                    </span>
                </a>
            </div>
        </section>
        <!-- Sección Contacto -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-2xl mx-auto px-4">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900">¿Tenés alguna consulta?</h2>
                    <p class="mt-3 text-gray-600">Completa el formulario y te responderemos a la brevedad.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6 bg-white p-8 rounded-2xl shadow">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                        <input v-model="form.name" type="text" required
                             class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input v-model="form.email" type="email" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono / WhatsApp</label>
                            <input v-model="form.phone" type="tel" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje</label>
                        <textarea v-model="form.message" rows="5" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <button type="submit" :disabled="loading"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 rounded-xl transition">
                        {{ loading ? 'Enviando...' : 'Enviar consulta' }}
                    </button>
                </form>
            </div>
        </section>
    </div>
</template>
