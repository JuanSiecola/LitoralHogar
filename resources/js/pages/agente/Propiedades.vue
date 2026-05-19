<template>
  <PanelLayout :nav-links="navLinks">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Mis Propiedades</h1>
      <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        + Nueva Propiedad
      </a>
    </div>

    <!-- Filtros por estado -->
    <div class="mb-4 flex gap-2">
      <button @click="filterEstado = null" 
              :class="!filterEstado ? 'bg-blue-600 text-white' : 'bg-gray-200'">
        Todas
      </button>
      <button @click="filterEstado = 'Disponible'" 
              :class="filterEstado === 'Disponible' ? 'bg-blue-600 text-white' : 'bg-gray-200'">
        Disponibles
      </button>
      <button @click="filterEstado = 'Vendida'" 
              :class="filterEstado === 'Vendida' ? 'bg-blue-600 text-white' : 'bg-gray-200'">
        Vendidas
      </button>
    </div>

    <!-- Tabla de propiedades -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div v-for="prop in propiedades.data" :key="prop.id" 
           class="bg-white rounded-xl shadow overflow-hidden">
        <img :src="prop.imagenes[0]?.url ?? '/placeholder.jpg'" 
             class="w-full h-48 object-cover" :alt="prop.titulo" />
        
        <div class="p-4">
          <h3 class="font-semibold text-gray-800">{{ prop.titulo }}</h3>
          <p class="text-blue-600 font-bold mt-1">${{ prop.precio.toLocaleString() }}</p>
          <p class="text-sm text-gray-500">{{ prop.ubicacion }}</p>
          
          <span :class="prop.estado_propiedad === 'Disponible' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                class="text-xs px-2 py-1 rounded-full mt-2 inline-block">
            {{ prop.estado_propiedad }}
          </span>

          <div class="flex gap-2 mt-3">
            <button class="flex-1 text-sm text-blue-600 border border-blue-300 rounded py-1 hover:bg-blue-50">
              Editar
            </button>
            <button class="flex-1 text-sm text-red-600 border border-red-300 rounded py-1 hover:bg-red-50">
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>

    <p v-if="!propiedades.data.length" class="text-gray-400">No tenés propiedades aún.</p>

    <!-- Paginación -->
    <div class="mt-6 flex justify-center gap-2">
      <!-- Links de paginación aquí -->
    </div>
  </PanelLayout>
</template>

<script setup>
import { LayoutDashboard, Home, MessageSquare } from 'lucide-vue-next'
import PanelLayout from '@/layouts/PanelLayout.vue'
import { ref } from 'vue'

defineProps(['propiedades'])

const filterEstado = ref(null)

const navLinks = [
  { label: 'Dashboard', href: '/agente/dashboard', icon: LayoutDashboard },
  { label: 'Mis Propiedades', href: '/agente/propiedades', icon: Home },
  { label: 'Consultas Recibidas', href: '/agente/consultas', icon: MessageSquare },
]
</script>