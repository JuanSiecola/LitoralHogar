<script setup lang="ts">
import { Home } from 'lucide-vue-next';
import PropertyCard from '@/components/PropertyCard.vue';
import type { Propiedad } from '@/types';

defineProps<{
    propiedades: Propiedad[];
    hayFiltrosActivos: boolean;
}>();

defineEmits<{
    limpiar: [];
}>();
</script>

<template>
    <div
        v-if="propiedades.length"
        class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3"
    >
        <PropertyCard
            v-for="propiedad in propiedades"
            :key="propiedad.id"
            v-bind="propiedad"
        />
    </div>

    <div
        v-else
        class="rounded-xl border border-dashed border-border bg-card px-6 py-16 text-center"
    >
        <Home class="mx-auto mb-4 h-10 w-10 text-muted-foreground" />
        <h2 class="text-lg font-semibold text-foreground">
            No hay propiedades que coincidan
        </h2>
        <p class="mt-2 text-sm text-muted-foreground">
            Probá ajustando o limpiando los filtros para ver más resultados.
        </p>
        <button
            v-if="hayFiltrosActivos"
            type="button"
            class="mt-5 inline-flex items-center gap-1 rounded-lg border border-border px-4 py-2 text-sm font-semibold text-foreground hover:border-primary hover:text-primary"
            @click="$emit('limpiar')"
        >
            Limpiar filtros
        </button>
    </div>
</template>
