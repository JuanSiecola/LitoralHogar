<script setup lang="ts">
import { X } from 'lucide-vue-next';

defineProps<{
    hayFiltrosActivos: boolean;
}>();

defineEmits<{
    limpiar: [];
}>();
</script>

<template>
    <aside>
        <form
            class="sticky top-6 space-y-5 rounded-xl border border-border bg-card p-5 shadow-sm"
            @submit.prevent
        >
            <div class="flex items-center justify-between border-b border-border pb-3">
                <div>
                    <h2 class="text-lg font-semibold text-foreground">Filtros</h2>
                    <p class="mt-0.5 text-xs text-muted-foreground">Ajusta tu búsqueda</p>
                </div>
                <button
                    v-if="hayFiltrosActivos"
                    type="button"
                    class="inline-flex items-center gap-1 rounded-md text-xs font-medium text-primary hover:underline"
                    @click="$emit('limpiar')"
                >
                    <X class="h-3.5 w-3.5" />
                    Limpiar
                </button>
            </div>

            <slot />
        </form>
    </aside>
</template>
