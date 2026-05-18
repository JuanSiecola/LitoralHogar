<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PropertyCard from '@/components/PropertyCard.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

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

function paginationLabel(label: string) {
    return label
        .replace('&laquo; Previous', 'Anterior')
        .replace('Next &raquo;', 'Siguiente');
}
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
    </section>
</template>