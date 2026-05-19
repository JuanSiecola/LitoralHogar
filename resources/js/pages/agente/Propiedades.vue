<template>
  <PanelLayout :nav-links="navLinks">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Mis Propiedades</h1>
      <Link href="/agente/propiedades/crear"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
        + Nueva Propiedad
      </Link>
    </div>

    <!-- Filtros por estado -->
    <div class="mb-4 flex gap-2">
      <button
        v-for="filtro in filtros" :key="filtro.label"
        @click="aplicarFiltro(filtro.valor)"
        :class="estadoActivo === filtro.valor
          ? 'bg-blue-600 text-white'
          : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
        class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors">
        {{ filtro.label }}
      </button>
    </div>

    <!-- Grid de propiedades -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div v-for="prop in propiedades.data" :key="prop.id"
           class="bg-white rounded-xl shadow overflow-hidden flex flex-col">
        <img :src="prop.imagenes?.[0]?.url ?? '/placeholder.jpg'"
             class="w-full h-48 object-cover" :alt="prop.titulo" />

        <div class="p-4 flex flex-col flex-1">
          <h3 class="font-semibold text-gray-800">{{ prop.titulo }}</h3>
          <p class="text-blue-600 font-bold mt-1">
            ${{ prop.detalle_propiedad?.precio?.toLocaleString() ?? 'Sin precio' }}
          </p>
          <p class="text-sm text-gray-500 mt-0.5">
            {{ [prop.ubicacion?.direccion, prop.ubicacion?.ciudad].filter(Boolean).join(', ') }}
          </p>

          <span :class="prop.estado_propiedad === 'Disponible'
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-700'"
                class="text-xs px-2 py-1 rounded-full mt-2 inline-block w-fit">
            {{ prop.estado_propiedad }}
          </span>

          <div class="flex gap-2 mt-auto pt-3">
            <Link :href="`/agente/propiedades/${prop.id}/editar`"
                  class="flex-1 text-center text-sm text-blue-600 border border-blue-300 rounded py-1.5 hover:bg-blue-50 transition-colors">
              Editar
            </Link>
            <button @click="confirmarEliminar(prop)"
                    class="flex-1 text-sm text-red-600 border border-red-300 rounded py-1.5 hover:bg-red-50 transition-colors">
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>

    <p v-if="!propiedades.data.length" class="text-gray-400 mt-6">No tenés propiedades aún.</p>

    <!-- Paginación -->
    <div v-if="propiedades.last_page > 1" class="mt-6 flex justify-center gap-2">
      <Link
        v-for="link in propiedades.links" :key="link.label"
        :href="link.url ?? '#'"
        v-html="link.label"
        :class="[
          'px-3 py-1.5 rounded text-sm border transition-colors',
          link.active
            ? 'bg-blue-600 text-white border-blue-600'
            : link.url
              ? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
              : 'bg-white text-gray-300 border-gray-200 cursor-not-allowed pointer-events-none'
        ]"
      />
    </div>

    <!-- Modal confirmación eliminar -->
    <div v-if="propAEliminar" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
      <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm mx-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">¿Eliminar propiedad?</h3>
        <p class="text-sm text-gray-500 mb-6">
          Estás por eliminar <strong>{{ propAEliminar.titulo }}</strong>. Esta acción no se puede deshacer.
        </p>
        <div class="flex gap-3">
          <button @click="propAEliminar = null"
                  class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
            Cancelar
          </button>
          <button @click="eliminar"
                  class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700 transition-colors">
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </PanelLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { LayoutDashboard, Home, MessageSquare } from 'lucide-vue-next'
import PanelLayout from '@/layouts/PanelLayout.vue'

defineProps(['propiedades'])

const estadoActivo = ref(null)
const propAEliminar = ref(null)

const filtros = [
  { label: 'Todas',       valor: null },
  { label: 'Disponibles', valor: 'Disponible' },
  { label: 'Vendidas',    valor: 'Vendida' },
]

const navLinks = [
  { label: 'Dashboard',           href: '/agente/dashboard',   icon: LayoutDashboard },
  { label: 'Mis Propiedades',     href: '/agente/propiedades', icon: Home },
  { label: 'Consultas Recibidas', href: '/agente/consultas',   icon: MessageSquare },
]

function aplicarFiltro(estado) {
  estadoActivo.value = estado
  router.get('/agente/propiedades', estado ? { estado } : {}, {
    preserveState: true,
    replace: true,
  })
}

function confirmarEliminar(prop) {
  propAEliminar.value = prop
}

function eliminar() {
  router.delete(`/agente/propiedades/${propAEliminar.value.id}`, {
    onSuccess: () => { propAEliminar.value = null },
  })
}
</script>