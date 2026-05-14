<template>
  <ClienteLayout>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">¡Bienvenido, {{ $page.props.auth.user.name }}!</h1>

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
      <div v-for="consulta in consultasRecientes" :key="consulta.id" class="border-b py-3 last:border-0">
        <p class="font-medium">{{ consulta.propiedad.titulo }}</p>
        <p class="text-sm text-gray-400">{{ consulta.created_at }}</p>
      </div>
      <p v-if="!consultasRecientes.length" class="text-gray-400 text-sm">No tenés consultas aún.</p>
    </div>
  </ClienteLayout>
</template>

<script setup>
import ClienteLayout from '@/layouts/ClienteLayout.vue'
defineProps(['totalFavoritos', 'totalConsultas', 'consultasRecientes'])
</script>