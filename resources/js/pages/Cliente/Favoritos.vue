<template>
  <PanelLayout :nav-links="navlink">

    <Head title="Mis Favoritos" />
    <h1 class="text-2xl font-bold mb-6">Mis Favoritos</h1>

    <div v-if="favoritos.data.length" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div v-for="prop in favoritos.data" :key="prop.id" class="bg-white rounded-xl shadow overflow-hidden">
        <img v-if="prop.imagenes[0]?.url" :src="prop.imagenes[0].url" class="w-full h-48 object-cover"
          :alt="prop.titulo" />
        <div v-else class="flex h-48 w-full items-center justify-center bg-muted text-muted-foreground">
          <Home class="h-12 w-12" />
        </div>

        <div class="p-4">
          <h3 class="font-semibold text-gray-800">{{ prop.titulo }}</h3>
          <p class="text-blue-600 font-bold mt-1">
            {{ formatPrecio(prop.detalle_propiedad?.precio ?? 0, prop.tipo_operacion) }}
          </p>
          <p class="text-sm text-gray-500">{{ prop.ubicacion?.localidad?.nombre ?? '' }}</p>

          <!-- Botón quitar -->
          <button @click="quitarFavorito(prop.id)" class="mt-3 w-full text-sm text-red-500 border border-red-300 rounded-lg py-1 hover:bg-red-50
  transition">
            Quitar de favoritos
          </button>
        </div>
      </div>
    </div>

    <p v-else class="text-gray-400">No tenés propiedades favoritas todavía.</p>
  </PanelLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3'
import { Home } from 'lucide-vue-next'
import PanelLayout from '@/layouts/PanelLayout.vue';
import { useClienteNav } from '@/composables/useClienteNav';
import { formatPrecio } from '@/lib/currency';

defineProps(['favoritos'])

const navlink = useClienteNav()

function quitarFavorito(propiedadId) {
  router.delete(`/cliente/favoritos/${propiedadId}`, { preserveScroll: true })
}
</script>