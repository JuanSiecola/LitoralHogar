<script setup lang="ts">
defineProps<{
    total: number;
    from: number | null;
    to: number | null;
    currentPage: number;
    lastPage: number;
}>();
</script>

<template>
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Propiedades</h1>
            <p class="mt-2 text-muted-foreground">
                Explora las propiedades disponibles para comprar o alquilar.
            </p>
        </div>

        <div v-if="total > 0" class="text-sm text-muted-foreground">
            <p>
                Mostrando
                <span class="font-semibold text-foreground">{{ from }}-{{ to }}</span>
                de
                <span class="font-semibold text-foreground">{{ total }}</span>
            </p>
            <p>
                Página
                <span class="font-semibold text-foreground">{{ currentPage }}</span>
                de
                <span class="font-semibold text-foreground">{{ lastPage }}</span>
            </p>
        </div>
    </div>
</template>
