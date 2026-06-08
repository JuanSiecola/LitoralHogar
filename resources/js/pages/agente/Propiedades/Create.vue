<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import PanelLayout from '@/layouts/PanelLayout.vue'
import { useAgenteNav } from '@/composables/useAgenteNav'
import { store } from '@/routes/agente/propiedades'
import PropiedadForm from './PropiedadForm.vue'

interface Amenidad {
    id: number
    nombre: string
}

interface Departamento {
    id: number
    nombre: string
}

const props = defineProps<{
    amenidades: Amenidad[]
    departamentos: Departamento[]
}>()

const navLinks = useAgenteNav()

const form = useForm({
    titulo: '',
    tipo_propiedad: '',
    tipo_operacion: '',
    estado_propiedad: 'Disponible',
    ubicacion: {
        direccion: '',
        departamento_id: null as number | null,
        localidad_id: null as number | null,
        latitud: null as number | null,
        longitud: null as number | null,
    },
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
    form.post(store.url())
}
</script>

<template>
    <PanelLayout :navLinks="navLinks">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Crear Propiedad</h1>
            <p class="text-sm text-gray-500 mt-1">Completá todos los datos para publicar la propiedad.</p>
        </div>

        <PropiedadForm
            :form="form"
            :amenidades="amenidades"
            :departamentos="departamentos"
            submit-label="Publicar propiedad"
            @submit="submit"
        />
    </PanelLayout>
</template>
