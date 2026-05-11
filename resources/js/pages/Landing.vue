<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import PropertyCard from '@/components/PropertyCard.vue'

interface Propiedad {
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

const props = defineProps<{
  propiedadesDestacadas: Propiedad[]
}>()

// Estado del buscador
const busqueda = ref({
  operacion: '',
  tipo: '',
  ciudad: '',
})

function buscar() {
  router.get('/propiedades', {
    tipo_operacion: busqueda.value.operacion,
    tipo_propiedad: busqueda.value.tipo,
    ciudad: busqueda.value.ciudad,
  }, { preserveState: false })
}
</script>

<template>
  <div class="min-h-screen bg-background">

    <!-- Hero -->
    <section class="relative bg-gradient-to-br from-primary/10 to-background py-20 px-4">
      <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-foreground">
          Encontrá tu próximo hogar en Pysandú, Uruguay
        </h1>
        <p class="mt-4 text-lg text-muted-foreground">
          Miles de propiedades en venta y alquiler en Pysandú.
        </p>

        <!-- Buscador -->
        <div class="mt-8 bg-card rounded-2xl shadow-card p-4 flex flex-col md:flex-row gap-3">
          <select v-model="busqueda.operacion" class="flex-1 rounded-lg border border-border px-3 py-2 text-sm bg-background">
            <option value="">Operación</option>
            <option value="Venta">Venta</option>
            <option value="Alquiler">Alquiler</option>
          </select>
          <select v-model="busqueda.tipo" class="flex-1 rounded-lg border border-border px-3 py-2 text-sm bg-background">
            <option value="">Tipo</option>
            <option value="Casa">Casa</option>
            <option value="Apartamento">Apartamento</option>
            <option value="Local">Local</option>
            <option value="Terreno">Terreno</option>
            <option value="Oficina">Oficina</option>
          </select>
          <input
            v-model="busqueda.ciudad"
            type="text"
            placeholder="Ciudad (ej: Montevideo)"
            class="flex-1 rounded-lg border border-border px-3 py-2 text-sm bg-background"
          />
          <button
            @click="buscar"
            class="bg-primary text-primary-foreground px-6 py-2 rounded-lg font-semibold hover:bg-primary/90 transition-colors"
          >
            Buscar
          </button>
        </div>
      </div>
    </section>
    <!-- Propiedades destacadas -->
    <section class="py-16 px-4 max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold text-foreground mb-2">Propiedades destacadas</h2>
            <p class="text-muted-foreground mb-8">Las últimas propiedades disponibles</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <PropertyCard
                v-for="prop in propiedadesDestacadas"
                :key="prop.id"
                v-bind="prop"
            />
        </div>

        <div class="mt-10 text-center">
            <a
                href="/propiedades"
                class="inline-block border border-primary text-primary px-8 py-3 rounded-lg font-semibold hover:bg-primary hover:text-primary-foreground transition-colors"
            >
            Ver todas las propiedades
            </a>
        </div>
    </section>
  </div>
</template>