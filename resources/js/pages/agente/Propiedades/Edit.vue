<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import PanelLayout from '@/layouts/PanelLayout.vue'
import PropiedadForm from './PropiedadForm.vue'  // ruta relativa
import { ChevronLeft, LayoutDashboard, Home, MessageSquare } from 'lucide-vue-next'

interface Amenidad {
    id: number
    nombre: string
}

interface ImagenExistente {
    id: number
    url: string
    es_principal: boolean
    orden: number
}

interface Propiedad {
    id: number
    titulo: string
    tipo_propiedad: string
    tipo_operacion: string
    estado_propiedad: string
    ubicacion: {
        direccion: string
        localidad: string
        departamento: string
        latitud: number | null
        longitud: number | null
    }
    detalle_propiedad: {
        nro_habitaciones: number
        nro_banios: number
        nro_garage: number
        superficie_total: number
        pisos: number
        precio: number
        anio_construccion: number
        estado_construccion: string
        deposito: number | null
        cant_meses_deposito: number | null
        expensas: number | null
        acepta_mascotas: boolean
    }
    amenidades: { id: number; nombre: string }[]
    imagenes: ImagenExistente[]
}

const props = defineProps<{
    propiedad: Propiedad
    amenidades: Amenidad[]
}>()

const navLinks = [
    { label: 'Dashboard',           href: '/agente/dashboard',   icon: LayoutDashboard },
    { label: 'Mis Propiedades',     href: '/agente/propiedades', icon: Home },
    { label: 'Consultas Recibidas', href: '/agente/consultas',   icon: MessageSquare },
]

const form = useForm({
    _method: 'patch',
    titulo:            props.propiedad.titulo,
    tipo_propiedad:    props.propiedad.tipo_propiedad,
    tipo_operacion:    props.propiedad.tipo_operacion,
    estado_propiedad:  props.propiedad.estado_propiedad,

    direccion:         props.propiedad.ubicacion.direccion,
    localidad:         props.propiedad.ubicacion.localidad,
    departamento:      props.propiedad.ubicacion.departamento,
    latitud:           props.propiedad.ubicacion.latitud   ?? undefined,
    longitud:          props.propiedad.ubicacion.longitud  ?? undefined,

    nro_habitaciones:  props.propiedad.detalle_propiedad.nro_habitaciones,
    nro_banios:        props.propiedad.detalle_propiedad.nro_banios,
    nro_garage:        props.propiedad.detalle_propiedad.nro_garage,
    superficie_total:  props.propiedad.detalle_propiedad.superficie_total,
    pisos:             props.propiedad.detalle_propiedad.pisos,
    precio:            props.propiedad.detalle_propiedad.precio,
    anio_construccion: props.propiedad.detalle_propiedad.anio_construccion,
    estado_construccion: props.propiedad.detalle_propiedad.estado_construccion,
    deposito:          props.propiedad.detalle_propiedad.deposito          ?? undefined,
    cant_meses_deposito: props.propiedad.detalle_propiedad.cant_meses_deposito ?? undefined,
    expensas:          props.propiedad.detalle_propiedad.expensas          ?? undefined,
    acepta_mascotas:   props.propiedad.detalle_propiedad.acepta_mascotas,

    amenidades:        props.propiedad.amenidades.map(a => a.id),

    imagenes:                [] as File[],
    imagen_principal_index:  0,
    imagenes_a_eliminar:     [] as number[],
})

function submit() {
    form.post(`/agente/propiedades/${props.propiedad.id}`)
}
</script>

<template>
    <PanelLayout :nav-links="navLinks">

        <div class="mb-6">
            <Link
                href="/agente/propiedades"
                class="mb-3 inline-flex items-center gap-1 text-sm text-neutral-500 transition-colors hover:text-neutral-800"
            >
                <ChevronLeft class="h-4 w-4" />
                Volver a propiedades
            </Link>
            <h1 class="text-2xl font-semibold text-gray-900">Editar Propiedad</h1>
            <p class="mt-1 text-sm text-gray-500">Modificá los datos de la propiedad.</p>
        </div>

        <PropiedadForm
            :form="form"
            :amenidades="amenidades"
            :imagenes-previas="propiedad.imagenes"
            cancel-href="/agente/propiedades"
            submit-label="Guardar cambios"
            @submit="submit"
        />

    </PanelLayout>
</template>