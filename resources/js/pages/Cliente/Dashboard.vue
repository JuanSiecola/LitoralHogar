<template>
  <ClienteLayout>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
      ¡Bienvenido, {{ nombreUsuario }}!
    </h1>

    <!-- Cards resumen -->
    <div class="grid grid-cols-2 gap-4 mb-8">
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <p class="text-3xl font-bold text-blue-600">{{ totalFavoritos }}</p>
        <p class="text-gray-500 mt-1">Propiedades favoritas</p>
      </div>
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <p class="text-3xl font-bold text-green-600">{{ totalConsultas }}</p>
        <p class="text-gray-500 mt-1">Consultas realizadas</p>
      </div>
    </div>

    <!-- Consultas recientes -->
    <div class="bg-white rounded-xl shadow p-6">
      <h2 class="text-lg font-semibold mb-4">Consultas recientes</h2>
      <div v-if="consultasRecientes && consultasRecientes.length">
        <div v-for="consulta in consultasRecientes" :key="consulta.id"
             class="border-b py-3 last:border-0">
          <p class="font-medium">{{ consulta.propiedad?.titulo ?? 'Propiedad sin título' }}</p>
          <p class="text-sm text-gray-400">{{ consulta.created_at }}</p>
        </div>
      </div>
      <p v-else class="text-gray-400 text-sm">No tenés consultas aún.</p>
    </div>
  </ClienteLayout>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import ClienteLayout from '@/layouts/ClienteLayout.vue'

defineProps(['totalFavoritos', 'totalConsultas', 'consultasRecientes'])

const page = usePage()

// Intenta mostrar nombre del perfil, si no el email
const nombreUsuario = computed(() => {
  const user = page.props.auth?.user
  if (!user) return ''
  return user.perfil_persona?.nombre
    ? `${user.perfil_persona.nombre} ${user.perfil_persona.apellido ?? ''}`
    : user.email
})
</script>