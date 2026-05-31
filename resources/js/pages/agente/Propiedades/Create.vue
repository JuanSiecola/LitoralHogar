<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import PanelLayout from '@/layouts/PanelLayout.vue'
import PropiedadForm from './PropiedadForm.vue'  // ruta relativa
import { Home, MessageSquare, LayoutDashboard } from 'lucide-vue-next'

interface Amenidad {
    id: number
    nombre: string
}

defineProps<{ amenidades: Amenidad[] }>()

const navLinks = [
    { label: 'Dashboard',           href: '/agente/dashboard',   icon: LayoutDashboard },
    { label: 'Mis Propiedades',     href: '/agente/propiedades', icon: Home },
    { label: 'Consultas Recibidas', href: '/agente/consultas',   icon: MessageSquare },
]

const form = useForm({
    titulo: '',
    tipo_propiedad: '',
    tipo_operacion: '',
    estado_propiedad: 'Disponible',
    direccion: '',
    localidad: '',
    departamento: '',
    latitud: undefined as number | undefined,
    longitud: undefined as number | undefined,
    nro_habitaciones: 0,
    nro_banios: 0,
    nro_garage: 0,
    superficie_total: undefined as number | undefined,
    pisos: 1,
    precio: undefined as number | undefined,
    anio_construccion: new Date().getFullYear(),
    estado_construccion: '',
    deposito: undefined as number | undefined,
    cant_meses_deposito: undefined as number | undefined,
    expensas: undefined as number | undefined,
    acepta_mascotas: false,
    amenidades: [] as number[],
    imagenes: [] as File[],
    imagen_principal_index: 0,
    imagenes_a_eliminar: [] as number[],
})

function submit() {
    form.post('/agente/propiedades')
}
</script>

<template>
    <PanelLayout :nav-links="navLinks">

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-foreground">Nueva propiedad</h1>
            <p class="mt-1 text-sm text-muted-foreground">Completá todos los datos para publicar la propiedad.</p>
        </div>

        <PropiedadForm
            :form="form"
            :amenidades="amenidades"
            submit-label="Publicar propiedad"
            cancel-href="/agente/propiedades"
            @submit="submit"
        />

    </PanelLayout>
</template>