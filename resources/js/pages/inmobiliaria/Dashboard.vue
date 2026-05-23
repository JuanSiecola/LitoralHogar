<script setup>
import PanelLayout from '@/layouts/PanelLayout.vue';
import { useInmobiliariaNav } from '@/composables/useInmobiliariaNav';

const navLinks = useInmobiliariaNav();
</script>

<template>
    <PanelLayout :navLinks="navLinks">
        <h1 class="mb-1 text-2xl font-semibold text-foreground">Dashboard</h1>
        <p class="text-sm text-muted-foreground">Bienvenido a tu panel de gestión.</p>

        <div class="mt-8 rounded-xl border border-dashed border-border bg-card p-12 text-center">
            <p class="text-sm text-muted-foreground">Stats de propiedades próximamente</p>
        </div>
    </PanelLayout>
</template>