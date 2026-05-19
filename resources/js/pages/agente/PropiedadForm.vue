<template>
  <PanelLayout :navLinks="navLinks">
    <PropiedadFormTabs
      :propiedad="propiedad"
      :submit-route="propiedad ? route('agente.propiedades.update', propiedad.id) : route('agente.propiedades.store')"
    />
  </PanelLayout>
</template>

<script setup>
import { LayoutDashboard, Home, MessageSquare } from 'lucide-vue-next'
import PanelLayout from '@/layouts/PanelLayout.vue'

defineProps(['propiedad'])

const navLinks = [
  { label: 'Dashboard',            href: '/agente/dashboard',   icon: LayoutDashboard },
  { label: 'Mis Propiedades',      href: '/agente/propiedades', icon: Home },
  { label: 'Consultas Recibidas',  href: '/agente/consultas',   icon: MessageSquare },
]
</script>