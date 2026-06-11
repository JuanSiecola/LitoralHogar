<script setup lang="ts">
defineProps<{ title: string; subtitle?: string; vacio?: boolean }>()
</script>

<template>
    <div class="rounded-xl border border-border bg-card p-6 shadow-card">
        <div class="mb-4">
            <h2 class="text-base font-semibold text-foreground">{{ title }}</h2>
            <p v-if="subtitle" class="text-xs text-muted-foreground">{{ subtitle }}</p>
        </div>
        <div v-if="vacio" class="flex h-[300px] items-center justify-center text-sm text-muted-foreground">
            Todavía no hay datos en este período.
        </div>
        <slot v-else />
    </div>
</template>