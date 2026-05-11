<script setup lang="ts">
interface Props {
  id: number
  titulo: string
  tipo_operacion: 'Venta' | 'Alquiler'
  tipo_propiedad: string
  precio: number
  nro_habitaciones: number
  nro_banios: number
  superficie_total: number
  ciudad: string
  departamento: string
  imagen_url?: string | null
}

const props = defineProps<Props>()

const precioFormateado = computed(() => {
  const num = new Intl.NumberFormat('es-UY').format(props.precio)
  return props.tipo_operacion === 'Alquiler'
    ? `USD ${num}/mes`
    : `USD ${num}`
})
</script>

<script lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <Link :href="`/propiedades/${id}`" class="group block rounded-xl overflow-hidden border border-border bg-card shadow-card hover:shadow-md transition-shadow duration-200">
    <!-- Imagen -->
    <div class="relative aspect-[4/3] overflow-hidden bg-muted">
      <img
        v-if="imagen_url"
        :src="imagen_url"
        :alt="titulo"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
      />
      <div v-else class="w-full h-full flex items-center justify-center text-muted-foreground">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75L12 3l9 6.75V21H3V9.75z"/>
        </svg>
      </div>
      <!-- Badge operación -->
      <span
        class="absolute top-3 left-3 text-xs font-semibold px-2.5 py-1 rounded-full"
        :class="tipo_operacion === 'Venta'
          ? 'bg-blue-600 text-white'
          : 'bg-emerald-600 text-white'"
      >
        {{ tipo_operacion }}
      </span>
    </div>

    <!-- Info -->
    <div class="p-4">
      <p class="text-lg font-bold text-primary">{{ precioFormateado }}</p>
      <h3 class="mt-1 text-sm font-medium text-foreground line-clamp-2">{{ titulo }}</h3>
      <p class="mt-1 text-xs text-muted-foreground">{{ ciudad }}, {{ departamento }}</p>

      <!-- Datos clave -->
      <div class="mt-3 flex items-center gap-4 text-xs text-muted-foreground border-t border-border pt-3">
        <span v-if="nro_habitaciones > 0" class="flex items-center gap-1">
          🛏 {{ nro_habitaciones }} hab.
        </span>
        <span v-if="nro_banios > 0" class="flex items-center gap-1">
          🚿 {{ nro_banios }} baños
        </span>
        <span class="flex items-center gap-1">
          📐 {{ superficie_total }} m²
        </span>
      </div>
    </div>
  </Link>
</template>