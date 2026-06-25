<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    currentPage: number;
    lastPage: number;
    total: number;
    urlConPagina: (pagina: number) => string;
    opciones: readonly number[];
}>();

const perPage = defineModel<string>('perPage', { required: true });

const paginasVisibles = computed<(number | '...')[]>(() => {
    const total = props.lastPage;
    const actual = props.currentPage;

    if (total <= 7) {
        return Array.from({ length: total }, (_, i) => i + 1);
    }

    const paginas: (number | '...')[] = [1];
    const inicio = Math.max(2, actual - 1);
    const fin = Math.min(total - 1, actual + 1);

    if (inicio > 2) paginas.push('...');
    for (let i = inicio; i <= fin; i++) paginas.push(i);
    if (fin < total - 1) paginas.push('...');

    paginas.push(total);
    return paginas;
});
</script>

<template>
    <div
        v-if="total > 0"
        class="mt-10 flex flex-col items-center justify-between gap-4 sm:flex-row"
    >
        <div class="flex items-center gap-2 text-sm text-muted-foreground">
            <label for="per-page" class="font-medium">Por página:</label>
            <select
                id="per-page"
                v-model="perPage"
                class="rounded-md border border-border bg-background px-2 py-1 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
            >
                <option
                    v-for="opcion in opciones"
                    :key="opcion"
                    :value="opcion.toString()"
                >
                    {{ opcion }}
                </option>
            </select>
        </div>

        <nav
            v-if="lastPage > 1"
            class="flex flex-wrap items-center justify-center gap-1.5"
            aria-label="Paginación de propiedades"
        >
            <Link
                v-if="currentPage > 1"
                :href="urlConPagina(currentPage - 1)"
                :preserve-scroll="false"
                preserve-state
                replace
                :only="['propiedades', 'filters']"
                class="rounded-md border border-border bg-background px-3 py-2 text-sm font-medium text-foreground hover:border-primary hover:text-primary"
            >
                Anterior
            </Link>

            <template v-for="(pagina, idx) in paginasVisibles" :key="idx">
                <span
                    v-if="pagina === '...'"
                    class="px-2 text-sm text-muted-foreground"
                >…</span>
                <Link
                    v-else
                    :href="urlConPagina(pagina)"
                    :preserve-scroll="false"
                    preserve-state
                    replace
                    :only="['propiedades', 'filters']"
                    class="min-w-10 rounded-md border px-3 py-2 text-center text-sm font-medium transition-colors"
                    :class="
                        pagina === currentPage
                            ? 'border-primary bg-primary text-primary-foreground'
                            : 'border-border bg-background text-foreground hover:border-primary hover:text-primary'
                    "
                >
                    {{ pagina }}
                </Link>
            </template>

            <Link
                v-if="currentPage < lastPage"
                :href="urlConPagina(currentPage + 1)"
                :preserve-scroll="false"
                preserve-state
                replace
                :only="['propiedades', 'filters']"
                class="rounded-md border border-border bg-background px-3 py-2 text-sm font-medium text-foreground hover:border-primary hover:text-primary"
            >
                Siguiente
            </Link>
        </nav>
    </div>
</template>
