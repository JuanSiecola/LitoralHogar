<script setup>
import PanelLayout from '@/layouts/PanelLayout.vue'
import { useInmobiliariaNav } from '@/composables/useInmobiliariaNav'

const navLinks = useInmobiliariaNav()
</script>

<template>
    <PanelLayout :navLinks="navLinks">
        <h1 class="text-2xl font-semibold text-gray-900 mb-1">Dashboard</h1>
        <p class="text-sm text-gray-500">Bienvenido a tu panel de gestión.</p>

        <div class="mt-8 rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-400">Stats de propiedades próximamente</p>
        </div>
    </PanelLayout>
</template>
