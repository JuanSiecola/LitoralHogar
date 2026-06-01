<template>
    <PanelLayout :nav-links="navlink">
        <h1 class="mb-6 text-2xl font-bold text-foreground">
            ¡Bienvenido, {{ nombreUsuario }}!
        </h1>

        <!-- Cards resumen -->
        <div class="mb-8 grid grid-cols-2 gap-4">
            <div class="rounded-xl border border-border bg-card p-6 text-center shadow-card">
                <p class="text-3xl font-bold text-primary">{{ totalFavoritos }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Propiedades favoritas</p>
            </div>
            <div class="rounded-xl border border-border bg-card p-6 text-center shadow-card">
                <p class="text-3xl font-bold text-secondary">{{ totalConsultas }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Consultas realizadas</p>
            </div>
        </div>

        <!-- Consultas recientes -->
        <div class="rounded-xl border border-border bg-card p-6 shadow-card">
            <h2 class="mb-4 text-lg font-semibold text-foreground">Consultas recientes</h2>
            <div v-if="consultasRecientes && consultasRecientes.length">
                <div
                    v-for="consulta in consultasRecientes"
                    :key="consulta.id"
                    class="border-b border-border py-3 last:border-0"
                >
                    <p class="font-medium text-foreground">{{ consulta.propiedad?.titulo ?? 'Propiedad sin título' }}</p>
                    <p class="text-sm text-muted-foreground">{{ consulta.created_at }}</p>
                </div>
            </div>
            <p v-else class="text-sm text-muted-foreground">No tenés consultas aún.</p>
        </div>
    </PanelLayout>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import PanelLayout from '@/layouts/PanelLayout.vue';
import { useClienteNav } from '@/composables/useClienteNav';

defineProps(['totalFavoritos', 'totalConsultas', 'consultasRecientes']);

const page = usePage();

const navlink = useClienteNav();

const nombreUsuario = computed(() => {
    const user = page.props.auth?.user;
    if (!user) return '';
    return user.perfil_persona?.nombre
        ? `${user.perfil_persona.nombre} ${user.perfil_persona.apellido ?? ''}`
        : user.email;
});
</script>