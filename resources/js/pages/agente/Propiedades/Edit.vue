<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import PanelLayout from '@/layouts/PanelLayout.vue'
import { update } from '@/routes/agente/propiedades'
import PropiedadForm from './PropiedadForm.vue'
import { Link } from '@inertiajs/vue3'
import { propiedades } from '@/routes/agente'
import { ChevronLeft } from 'lucide-vue-next'
import { useAgenteNav } from '@/composables/useAgenteNav.js'

interface Amenidad {
    id: number
    nombre: string
}

interface Departamento {
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
        departamento_id: number
        localidad_id: number
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
    amenidades: Amenidad[]
    imagenes: ImagenExistente[]
}

const props = defineProps<{
    propiedad: Propiedad
    amenidades: Amenidad[]
    departamentos: Departamento[]
}>()

const navLinks = useAgenteNav()

const form = useForm({
    _method: 'patch',
    titulo: props.propiedad.titulo,
    tipo_propiedad: props.propiedad.tipo_propiedad,
    tipo_operacion: props.propiedad.tipo_operacion,
    estado_propiedad: props.propiedad.estado_propiedad,
    ubicacion: {
        direccion: props.propiedad.ubicacion.direccion,
        departamento_id: props.propiedad.ubicacion.departamento_id as number | null,
        localidad_id: props.propiedad.ubicacion.localidad_id as number | null,
        latitud: props.propiedad.ubicacion.latitud ? Number(props.propiedad.ubicacion.latitud) : null,
        longitud: props.propiedad.ubicacion.longitud ? Number(props.propiedad.ubicacion.longitud) : null,
    },
    nro_habitaciones: props.propiedad.detalle_propiedad.nro_habitaciones,
    nro_banios: props.propiedad.detalle_propiedad.nro_banios,
    nro_garage: props.propiedad.detalle_propiedad.nro_garage,
    superficie_total: props.propiedad.detalle_propiedad.superficie_total,
    pisos: props.propiedad.detalle_propiedad.pisos,
    precio: props.propiedad.detalle_propiedad.precio,
    anio_construccion: props.propiedad.detalle_propiedad.anio_construccion,
    estado_construccion: props.propiedad.detalle_propiedad.estado_construccion,
    deposito: props.propiedad.detalle_propiedad.deposito ?? undefined,
    cant_meses_deposito: props.propiedad.detalle_propiedad.cant_meses_deposito ?? undefined,
    expensas: props.propiedad.detalle_propiedad.expensas ?? undefined,
    acepta_mascotas: props.propiedad.detalle_propiedad.acepta_mascotas,
    amenidades: props.propiedad.amenidades.map(a => a.id),
    imagenes: [] as File[],
    imagen_principal_index: 0,
    imagenes_a_eliminar: [] as number[],
})

function submit() {
    form.post(update.url(props.propiedad.id))
}
</script>

<template>
    <PanelLayout :navLinks="navLinks">
        <div class="mb-6">
            <Link :href="propiedades.url()"
                class="inline-flex items-center gap-1 text-sm text-neutral-500 hover:text-neutral-800 transition-colors mb-3">
                <ChevronLeft class="w-4 h-4" />
                Volver a propiedades
            </Link>
            <h1 class="text-2xl font-semibold text-gray-900">Editar Propiedad</h1>
            <p class="text-sm text-gray-500 mt-1">Modificá los datos de la propiedad.</p>
        </div>

        <PropiedadForm
            :form="form"
            :amenidades="amenidades"
            :departamentos="departamentos"
            :imagenes-previas="propiedad.imagenes"
            :cancel-href="propiedades.url()"
            submit-label="Guardar cambios"
            @submit="submit"
        />
    </PanelLayout>
</template>