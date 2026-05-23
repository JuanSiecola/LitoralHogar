<script setup lang="ts">
import type { Component } from 'vue'

defineProps<{
    icon: Component
    title: string
    description?: string
}>()
</script>

<template>
    <section class="bg-card rounded-xl border border-border p-6 mb-5 shadow-card">
        <div class="flex items-center gap-3 pb-4 mb-5 border-b border-border">
            <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
                <component :is="icon" class="w-4 h-4" />
            </div>
            <div>
                <h2 class="text-xl font-semibold text-foreground">{{ title }}</h2>
                <p v-if="description" class="text-sm text-muted-foreground">{{ description }}</p>
            </div>
        </div>
        <slot />
    </section>
</template>