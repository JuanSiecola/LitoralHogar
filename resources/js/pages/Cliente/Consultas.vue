<template>
  <PanelLayout :nav-links="navlink">
    <h1 class="text-2xl font-bold mb-6">Mis Consultas</h1>

    <div class="space-y-4">
      <div v-for="consulta in consultas.data" :key="consulta.id"
           class="bg-white rounded-xl shadow p-5">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="font-semibold text-gray-800">{{ consulta.propiedad.titulo }}</h3>
            <p class="text-sm text-gray-500 mt-1">{{ consulta.mensaje }}</p>
          </div>
          <span :class="estadoBadge(consulta.estado)"
                class="text-xs px-2 py-1 rounded-full font-medium">
            {{ consulta.estado }}
          </span>
        </div>

        <!-- Respuesta del agente -->
        <div v-if="consulta.respuesta" class="mt-3 bg-blue-50 rounded-lg p-3">
          <p class="text-sm font-medium text-blue-700">Respuesta:</p>
          <p class="text-sm text-blue-600">{{ consulta.respuesta }}</p>
        </div>

        <p class="text-xs text-gray-400 mt-2">{{ consulta.created_at }}</p>
      </div>
    </div>

    <p v-if="!consultas.data.length" class="text-gray-400">No realizaste consultas todavía.</p>
  </PanelLayout>
</template>

<script setup>
import PanelLayout from '@/layouts/PanelLayout.vue';
import { useClienteNav } from '@/composables/useClienteNav';

defineProps(['consultas'])

const navlink = useClienteNav()


function estadoBadge(estado) {
  return estado === 'respondida'
    ? 'bg-green-100 text-green-700'
    : 'bg-yellow-100 text-yellow-700'
}
</script>