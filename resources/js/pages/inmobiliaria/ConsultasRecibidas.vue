<template>
  <PanelLayout :nav-links="navLinks">
    <h1 class="text-2xl font-bold mb-6">Consultas Recibidas</h1>

    <div class="space-y-4">
      <div v-for="consulta in consultas.data" :key="consulta.id"
           class="bg-white rounded-xl shadow p-5">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="font-semibold text-gray-800">{{ consulta.propiedad?.titulo ?? 'Propiedad' }}</h3>
            <p class="text-sm text-gray-600 mt-1">
              <strong>De:</strong> {{ nombreDe(consulta.user) }}
            </p>
          </div>
          <span v-if="consulta.no_leidos > 0"
                class="text-xs px-2 py-1 rounded-full font-medium bg-yellow-100 text-yellow-700">
            {{ consulta.no_leidos }} sin leer
          </span>
          <span v-else class="text-xs px-2 py-1 rounded-full font-medium bg-green-100 text-green-700">
            Al día
          </span>
        </div>

        <!-- Hilo de mensajes -->
        <div class="mt-4 max-h-72 overflow-y-auto space-y-2 pr-1">
          <div v-for="msg in consulta.mensajes" :key="msg.id"
               class="flex" :class="esMio(msg) ? 'justify-end' : 'justify-start'">
            <div class="max-w-[75%] rounded-lg px-3 py-2 text-sm"
                 :class="esMio(msg) ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-800'">
              <p v-if="!esMio(msg)" class="text-xs font-medium opacity-70 mb-0.5">
                {{ nombreDe(msg.user) }}
              </p>
              <p>{{ msg.mensaje }}</p>
              <p class="text-[10px] opacity-60 mt-1">{{ formatearFecha(msg.created_at) }}</p>
            </div>
          </div>
        </div>

        <!-- Responder en el hilo -->
        <div class="mt-3">
          <textarea v-model="respuestas[consulta.id]"
                    placeholder="Escribí tu respuesta..."
                    rows="2"
                    class="w-full p-2 border rounded-lg text-sm"></textarea>
          <button @click="responder(consulta.id)"
                  :disabled="enviando === consulta.id"
                  class="mt-2 px-4 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700 disabled:opacity-60">
            {{ enviando === consulta.id ? 'Enviando...' : 'Responder' }}
          </button>
        </div>
      </div>
    </div>

    <p v-if="!consultas.data.length" class="text-gray-400">No tenés consultas aún.</p>
  </PanelLayout>
</template>

<script setup>
import { router, usePage } from '@inertiajs/vue3'
import PanelLayout from '@/layouts/PanelLayout.vue'
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useInmobiliariaNav } from '@/composables/useInmobiliariaNav'

defineProps(['consultas'])

const page = usePage()
const miId = computed(() => page.props.auth?.user?.id)

const respuestas = ref({})
const enviando = ref(null)
const navLinks = useInmobiliariaNav()

function nombreDe(user) {
  const perfil = user?.perfil_persona
  if (perfil?.nombre) {
    return `${perfil.nombre} ${perfil.apellido ?? ''}`.trim()
  }
  return user?.email ?? 'Usuario'
}

function esMio(msg) {
  return msg.usuario_id === miId.value
}

function formatearFecha(fecha) {
  return new Date(fecha).toLocaleString('es-UY', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' })
}

function responder(consultaId) {
  const mensaje = respuestas.value[consultaId]
  if (!mensaje || !mensaje.trim()) return

  enviando.value = consultaId
  router.post(`/consultas/${consultaId}/mensajes`, { mensaje }, {
    preserveScroll: true,
    onSuccess: () => { respuestas.value[consultaId] = '' },
    onFinish: () => { enviando.value = null },
  })
}

// Polling simple para refrescar la lista de consultas cada 15s
let intervalo = null
onMounted(() => {
  intervalo = setInterval(() => {
    router.reload({ only: ['consultas'], preserveScroll: true })
  }, 15000)
})
onUnmounted(() => {
  if (intervalo) clearInterval(intervalo)
})
</script>