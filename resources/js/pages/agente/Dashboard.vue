<template>
    <PanelLayout :nav-links="navLinks">
        <h1 class="mb-6 text-2xl font-bold text-foreground">Dashboard Agente</h1>

        <div class="mb-8 grid grid-cols-3 gap-4">
            <div class="rounded-xl border border-border bg-card p-6 text-center shadow-card">
                <p class="text-3xl font-bold text-primary">{{ propsActivas }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Propiedades activas</p>
            </div>
            <div class="rounded-xl border border-border bg-card p-6 text-center shadow-card">
                <p class="text-3xl font-bold text-secondary">{{ totalVistas }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Total vistas</p>
            </div>
            <div class="rounded-xl border border-border bg-card p-6 text-center shadow-card">
                <p class="text-3xl font-bold text-accent">{{ consultasPendientes }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Consultas pendientes</p>
            </div>
        </div>
    </PanelLayout>
</template>

<script setup>
import { LayoutDashboard, Home, MessageSquare } from 'lucide-vue-next';
import PanelLayout from '@/layouts/PanelLayout.vue';

defineProps(['propsActivas', 'totalVistas', 'consultasPendientes']);

const navLinks = [
    { label: 'Dashboard',           href: '/agente/dashboard',   icon: LayoutDashboard },
    { label: 'Mis Propiedades',     href: '/agente/propiedades', icon: Home },
    { label: 'Consultas Recibidas', href: '/agente/consultas',   icon: MessageSquare },
];
</script>