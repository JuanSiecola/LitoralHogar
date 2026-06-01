<template>
  <PanelLayout :nav-links="navLinks">
    <h1 class="text-2xl font-bold mb-6">Consultas Recibidas</h1>

    <div class="space-y-4">
      <div v-for="consulta in consultas.data" :key="consulta.id"
           class="bg-white rounded-xl shadow p-5">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="font-semibold text-gray-800">{{ consulta.propiedad.titulo }}</h3>
            <p class="text-sm text-gray-600 mt-1">
              <strong>De:</strong> {{ consulta.user.name }}
            </p>
            <p class="text-sm text-gray-500 mt-1">{{ consulta.mensaje }}</p>
          </div>
          <span :class="estadoBadge(consulta.estado)"
                class="text-xs px-2 py-1 rounded-full font-medium">
            {{ consulta.estado }}
          </span>
        </div>

        <!-- Respuesta del agente (si existe) -->
        <div v-if="consulta.respuesta" class="mt-3 bg-blue-50 rounded-lg p-3">
          <p class="text-sm font-medium text-blue-700">Tu Respuesta:</p>
          <p class="text-sm text-blue-600">{{ consulta.respuesta }}</p>
        </div>

        <!-- Formulario para responder (si no está respondida) -->
        <div v-if="consulta.estado !== 'respondida'" class="mt-3">
          <textarea v-model="respuestas[consulta.id]"
                    placeholder="Escribí tu respuesta..."
                    class="w-full p-2 border rounded-lg text-sm"></textarea>
          <button @click="responder(consulta.id)"
                  class="mt-2 px-4 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
            Responder
          </button>
        </div>

        <p class="text-xs text-gray-400 mt-2">{{ consulta.created_at }}</p>
      </div>
    </div>

    <p v-if="!consultas.data.length" class="text-gray-400">No tenés consultas aún.</p>
  </PanelLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import PanelLayout from '@/layouts/PanelLayout.vue'
import { ref } from 'vue'
import { useAgenteNav } from '@/composables/useAgenteNav'

defineProps(['consultas'])

const respuestas = ref({})
const navLinks = useAgenteNav()

function estadoBadge(estado) {
  return estado === 'respondida'
    ? 'bg-green-100 text-green-700'
    : 'bg-yellow-100 text-yellow-700'
}

function responder(consultaId) {
  router.post(route('agente.consultas.responder', consultaId), {
    respuesta: respuestas.value[consultaId]
  })
}
</script>