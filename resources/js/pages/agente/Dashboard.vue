<template>
    <PanelLayout :nav-links="navLinks">

        <!-- Header -->
        <div class="mb-8 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-foreground">Dashboard</h1>
                <p class="mt-1 text-sm text-muted-foreground">Bienvenido de nuevo, {{ agente?.nombre ?? 'Agente' }}</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-border bg-card p-6 shadow-card">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-muted-foreground">Propiedades activas</p>
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10">
                        <Home class="h-4 w-4 text-primary" />
                    </div>
                </div>
                <p class="mt-3 text-3xl font-bold text-foreground">{{ propsActivas }}</p>
                <p class="mt-1 text-xs text-muted-foreground">Publicadas y disponibles</p>
            </div>


            <div class="rounded-xl border border-border bg-card p-6 shadow-card">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-muted-foreground">Consultas pendientes</p>
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500/10">
                        <MessageSquare class="h-4 w-4 text-amber-500" />
                    </div>
                </div>
                <p class="mt-3 text-3xl font-bold text-foreground">{{ consultasPendientes }}</p>
                <p class="mt-1 text-xs text-muted-foreground">Sin responder todavía</p>
            </div>
        </div>

        <!-- Últimas consultas -->
        <div class="rounded-xl border border-border bg-card shadow-card">
            <div class="flex items-center justify-between border-b border-border px-6 py-4">
                <h2 class="text-base font-semibold text-foreground">Últimas consultas</h2>
                <Link
                    href="/agente/consultas"
                    class="text-sm font-medium text-primary hover:underline"
                >
                    Ver todas
                </Link>
            </div>

            <div v-if="ultimasConsultas && ultimasConsultas.length" class="divide-y divide-border">
                <div
                    v-for="consulta in ultimasConsultas"
                    :key="consulta.id"
                    class="flex items-start gap-4 px-6 py-4"
                >
                    <!-- Avatar inicial -->
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-muted text-sm font-semibold text-muted-foreground">
                        {{ consulta.user?.nombre?.charAt(0)?.toUpperCase() ?? '?' }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="truncate text-sm font-medium text-foreground">
                                {{ consulta.user?.nombre }}
                            </p>
                            <span
                                class="shrink-0 rounded-full px-2 py-0.5 text-xs font-medium"
                                :class="consulta.estado === 'respondida'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-amber-100 text-amber-700'"
                            >
                                {{ consulta.estado === 'respondida' ? 'Respondida' : 'Pendiente' }}
                            </span>
                        </div>
                        <p class="mt-0.5 truncate text-xs text-muted-foreground">
                            {{ consulta.propiedad?.titulo }}
                        </p>
                        <p class="mt-1 line-clamp-1 text-sm text-muted-foreground">
                            {{ consulta.mensaje }}
                        </p>
                    </div>
                </div>
            </div>

            <div v-else class="px-6 py-10 text-center">
                <MessageSquare class="mx-auto mb-3 h-8 w-8 text-muted-foreground/40" />
                <p class="text-sm text-muted-foreground">Todavía no recibiste consultas.</p>
            </div>
        </div>

    </PanelLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { LayoutDashboard, Home, MessageSquare, Eye, Plus } from 'lucide-vue-next'
import PanelLayout from '@/layouts/PanelLayout.vue'

defineProps({
    propsActivas:        { type: Number, default: 0 },
    totalVistas:         { type: Number, default: 0 },
    consultasPendientes: { type: Number, default: 0 },
    ultimasConsultas:    { type: Array,  default: () => [] },
    agente:              { type: Object, default: null },
})

const navLinks = [
    { label: 'Dashboard',           href: '/agente/dashboard',   icon: LayoutDashboard },
    { label: 'Mis Propiedades',     href: '/agente/propiedades', icon: Home },
    { label: 'Consultas Recibidas', href: '/agente/consultas',   icon: MessageSquare },
]
</script>