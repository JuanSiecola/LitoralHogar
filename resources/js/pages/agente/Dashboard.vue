<template>
  <AgenteLayout>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Agente</h1>

    <div class="grid grid-cols-3 gap-4 mb-8">
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <p class="text-3xl font-bold text-green-600">{{ propsActivas }}</p>
        <p class="text-gray-500 mt-1">Propiedades activas</p>
      </div>
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <p class="text-3xl font-bold text-blue-600">{{ totalVistas }}</p>
        <p class="text-gray-500 mt-1">Total vistas</p>
      </div>
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <p class="text-3xl font-bold text-yellow-600">{{ consultasPendientes }}</p>
        <p class="text-gray-500 mt-1">Consultas pendientes</p>
      </div>
    </div>
  </AgenteLayout>
</template>

<script setup>
import AgenteLayout from '@/layouts/AgenteLayout.vue'
defineProps(['propsActivas', 'totalVistas', 'consultasPendientes'])
</script>