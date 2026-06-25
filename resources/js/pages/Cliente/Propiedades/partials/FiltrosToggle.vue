<script setup lang="ts">
import { SlidersHorizontal, X } from 'lucide-vue-next';

defineProps<{
    hayFiltrosActivos: boolean;
}>();

defineEmits<{
    limpiar: [];
}>();

const abierto = defineModel<boolean>('abierto', { required: true });
</script>

<template>
    <div class="mb-4 flex items-center justify-between gap-3 lg:hidden">
        <button
            type="button"
            class="inline-flex items-center gap-2 rounded-lg border border-border bg-background px-4 py-2 text-sm font-semibold text-foreground"
            @click="abierto = !abierto"
        >
            <SlidersHorizontal class="h-4 w-4" />
            {{ abierto ? 'Ocultar filtros' : 'Mostrar filtros' }}
        </button>
        <button
            v-if="hayFiltrosActivos"
            type="button"
            class="inline-flex items-center gap-1 text-sm font-medium text-primary"
            @click="$emit('limpiar')"
        >
            <X class="h-4 w-4" />
            Limpiar
        </button>
    </div>
</template>
