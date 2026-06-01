<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { InertiaForm } from '@inertiajs/vue3'
import { useImagenes } from '@/composables/useImagenes'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { FormMessage } from '@/components/ui/form'
import AmenidadesSelector from '@/components/AmenidadesSelector.vue'
import { Home, MapPin, ImageIcon, PawPrint, DollarSign, Sparkles, X } from 'lucide-vue-next'
import InputError from '@/components/InputError.vue'
import FormSection from '@/components/FormSection.vue'
import UbicacionInput from '@/components/UbicacionInput.vue'

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

interface Departamento {
    id: number
    nombre: string
}

interface FormData {
    titulo: string
    tipo_propiedad: string
    tipo_operacion: string
    estado_propiedad: string
    ubicacion: {
        direccion: string
        departamento_id: number | null
        localidad_id: number | null
        latitud: number | null
        longitud: number | null
    }
    nro_habitaciones: number
    nro_banios: number
    nro_garage: number
    superficie_total: number | undefined
    pisos: number
    precio: number | undefined
    anio_construccion: number
    estado_construccion: string
    deposito: number | undefined
    cant_meses_deposito: number | undefined
    expensas: number | undefined
    acepta_mascotas: boolean
    amenidades: number[]
    imagenes: File[]
    imagen_principal_index: number
    imagenes_a_eliminar: number[]
}

const props = defineProps<{
    form: InertiaForm<FormData>
    amenidades: Amenidad[]
    departamentos: Departamento[]
    imagenesPrevias?: ImagenExistente[]
    submitLabel: string
    cancelHref?: string
}>()

const emit = defineEmits<{ submit: [] }>()

const { previews, onImagenesChange, eliminarImagen } = useImagenes(props.form)

function handleSubmit() {
    props.form.amenidades = props.form.amenidades.map(Number)
    emit('submit')
}

function toggleEliminarImagen(id: number) {
    const idx = props.form.imagenes_a_eliminar.indexOf(id)
    if (idx === -1) {
        props.form.imagenes_a_eliminar = [...props.form.imagenes_a_eliminar, id]
    } else {
        props.form.imagenes_a_eliminar = props.form.imagenes_a_eliminar.filter(i => i !== id)
    }
}

</script>

<template>
    <form @submit.prevent="handleSubmit">

        <FormSection :icon="Home" title="Datos principales"
            description="Completa los datos principales de la propiedad.">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-1.5">
                    <Input id="titulo" type="text" label="Titulo" required v-model="form.titulo"
                        placeholder="Ej: Hermoso apartamento en la Blanqueada" />
                    <InputError :message="form.errors.titulo" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <Label for="tipo_propiedad">Tipo de Propiedad</Label>
                        <Select v-model="form.tipo_propiedad">
                            <SelectTrigger id="tipo_propiedad" class="w-full">
                                <SelectValue placeholder="Seleccione..." />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="Casa">Casa</SelectItem>
                                <SelectItem value="Apartamento">Apartamento</SelectItem>
                                <SelectItem value="Local">Local comercial</SelectItem>
                                <SelectItem value="Terreno">Terreno</SelectItem>
                                <SelectItem value="Oficina">Oficina</SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.tipo_propiedad" />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <Label for="operacion">Tipo de Operación</Label>
                        <Select v-model="form.tipo_operacion">
                            <SelectTrigger id="operacion" class="w-full">
                                <SelectValue placeholder="Seleccione..." />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="Venta">Venta</SelectItem>
                                <SelectItem value="Alquiler">Alquiler</SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.tipo_operacion" />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <Label for="estado">Estado</Label>
                        <Select v-model="form.estado_propiedad">
                            <SelectTrigger id="estado" class="w-full">
                                <SelectValue placeholder="Seleccione..." />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="Disponible">Disponible</SelectItem>
                                <SelectItem value="Reservada">Reservada</SelectItem>
                                <SelectItem value="Vendida">Vendida</SelectItem>
                                <SelectItem value="Alquilada">Alquilada</SelectItem>
                                <SelectItem value="Pausada">Pausada</SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.estado_propiedad" />
                    </div>
                </div>
            </div>
        </FormSection>

        <FormSection :icon="MapPin" title="Ubicación" description="Dirección y coordenadas">
            <UbicacionInput v-model="form.ubicacion" :departamentos="departamentos" :errors="{
                direccion: form.errors['ubicacion.direccion'],
                departamento_id: form.errors['ubicacion.departamento_id'],
                localidad_id: form.errors['ubicacion.localidad_id'],
                latitud: form.errors['ubicacion.latitud'],
                longitud: form.errors['ubicacion.longitud'],
            }" />
        </FormSection>

        <FormSection :icon="DollarSign" title="Detalles de la propiedad" description="Características físicas y precio">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <Input id="nro_habitaciones" label="Habitaciones" type="number" required :min="0" :max="20"
                    v-model="form.nro_habitaciones" />
                <InputError :message="form.errors.nro_habitaciones" />
                <Input id="nro_banios" label="Baños" type="number" :min="0" :max="10" required
                    v-model="form.nro_banios" />
                <InputError :message="form.errors.nro_banios" />
                <Input id="nro_garage" label="Garage" type="number" :min="0" :max="5" required
                    v-model="form.nro_garage" />
                <InputError :message="form.errors.nro_garage" />
                <Input id="pisos" label="Pisos" type="number" :min="1" required v-model="form.pisos" />
                <InputError :message="form.errors.pisos" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <Input id="superficie_total" label="Superficie (m²)" required placeholder="240" :min="1"
                    v-model="form.superficie_total" />
                <InputError :message="form.errors.superficie_total" />
                <Input id="precio" type="number" label="Precio (USD)" required placeholder="200000" :min="0"
                    v-model="form.precio" />
                <InputError :message="form.errors.precio" />
                <Input id="anio_construccion" label="Año construcción" type="number" required :min="1900"
                    :max="new Date().getFullYear()" v-model="form.anio_construccion" />
                <InputError :message="form.errors.anio_construccion" />
                <div class="flex flex-col gap-1.5">
                    <Label for="estado_construccion">Estado de construcción</Label>
                    <Select required v-model="form.estado_construccion">
                        <SelectTrigger id="estado_construccion" class="w-full">
                            <SelectValue placeholder="Seleccione..." />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="Nuevo">Nuevo</SelectItem>
                            <SelectItem value="Usado">Usado</SelectItem>
                            <SelectItem value="Reciclado">Reciclado</SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.estado_construccion" />
                </div>
            </div>

            <div class="mt-5">
                <label for="acepta_mascotas"
                    class="flex items-center gap-2.5 p-3 rounded-lg border border-neutral-200 cursor-pointer hover:bg-neutral-50 transition-colors w-fit"
                    :class="{ 'border-primary bg-primary/5': form.acepta_mascotas }">
                    <input id="acepta_mascotas" type="checkbox"
                        class="size-4 rounded border-neutral-300 text-primary focus:ring-primary"
                        v-model="form.acepta_mascotas" />
                    <span class="flex items-center gap-1.5 text-sm text-neutral-700 select-none">
                        <PawPrint class="w-3.5 h-3.5 text-neutral-400" />
                        Acepta mascotas
                    </span>
                </label>
            </div>

            <div v-if="form.tipo_operacion === 'Alquiler'"
                class="mt-5 rounded-lg border border-neutral-200 bg-neutral-50 p-4 space-y-4">
                <div class="flex items-center gap-2">
                    <DollarSign class="w-4 h-4 text-neutral-400" />
                    <p class="text-sm font-semibold text-neutral-700">Condiciones del alquiler</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <Input id="deposito" label="Depósito" type="number" placeholder="0" v-model="form.deposito"
                        :error="form.errors.deposito" />
                    <Input id="cant_meses_deposito" label="Meses de depósito" type="number" placeholder="1"
                        v-model="form.cant_meses_deposito" :error="form.errors.cant_meses_deposito" />
                    <Input id="expensas" label="Expensas" type="number" placeholder="0" v-model="form.expensas"
                        :error="form.errors.expensas" />
                </div>
            </div>

            <p v-else class="mt-5 text-xs text-neutral-400 italic">
                Las condiciones de alquiler aparecen cuando seleccionás "Alquiler" en tipo de operación.
            </p>
        </FormSection>

        <FormSection v-if="amenidades.length" :icon="Sparkles" title="Comodidades"
            description="Seleccioná las comodidades disponibles">
            <AmenidadesSelector v-model="form.amenidades" :amenidades="amenidades" />
        </FormSection>

        <FormSection :icon="ImageIcon" title="Imágenes" description="Fotos de la propiedad">

            <!-- Imágenes existentes con botón de eliminar (solo en edición) -->
            <template v-if="imagenesPrevias !== undefined">
                <p class="text-xs font-medium text-neutral-500 mb-3">
                    Fotos actuales
                    <span v-if="imagenesPrevias.length" class="text-neutral-400 font-normal"> — hacé clic en la X para
                        marcar para eliminar</span>
                </p>
                <div v-if="imagenesPrevias.length" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-6">
                    <div v-for="imagen in imagenesPrevias" :key="imagen.id"
                        class="relative group rounded-xl overflow-hidden border-2 transition-all duration-200" :class="form.imagenes_a_eliminar.includes(imagen.id)
                            ? 'border-red-400 opacity-60'
                            : imagen.es_principal ? 'border-primary ring-2 ring-primary/30' : 'border-neutral-200'">
                        <img :src="imagen.url" class="w-full aspect-4/3 object-cover" />
                        <span v-if="imagen.es_principal && !form.imagenes_a_eliminar.includes(imagen.id)"
                            class="absolute bottom-0 left-0 right-0 text-center text-[10px] font-semibold bg-primary text-white py-1">
                            Portada
                        </span>
                        <div v-if="form.imagenes_a_eliminar.includes(imagen.id)"
                            class="absolute inset-0 bg-red-500/50 flex items-center justify-center">
                            <span class="text-white text-xs font-semibold">Se eliminará</span>
                        </div>
                        <button type="button"
                            class="absolute top-1.5 right-1.5 w-6 h-6 flex items-center justify-center rounded-full text-white transition-all"
                            :class="form.imagenes_a_eliminar.includes(imagen.id)
                                ? 'bg-red-600 opacity-100'
                                : 'bg-black/60 opacity-0 group-hover:opacity-100 hover:bg-red-600'"
                            :title="form.imagenes_a_eliminar.includes(imagen.id) ? 'Deshacer' : 'Marcar para eliminar'"
                            @click="toggleEliminarImagen(imagen.id)">
                            <X class="w-4 h-4" />
                        </button>
                    </div>
                </div>
                <p v-else class="text-sm text-neutral-400 italic mb-5">Esta propiedad no tiene fotos todavía.</p>

                <p class="text-xs font-medium text-neutral-500 mb-2">Agregar fotos nuevas</p>
            </template>

            <label
                class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-neutral-300 rounded-xl cursor-pointer hover:border-primary hover:bg-primary/5 transition-colors group">
                <ImageIcon class="w-8 h-8 text-neutral-300 group-hover:text-primary transition-colors mb-2" />
                <span class="text-sm font-medium text-neutral-500 group-hover:text-primary transition-colors">
                    Hacé clic para agregar fotos
                </span>
                <span class="text-xs text-neutral-400 mt-1">JPG, PNG, WEBP — máx. 5 MB por imagen</span>
                <input type="file" multiple accept="image/*" class="hidden" @change="onImagenesChange" />
            </label>

            <div v-if="previews.length" class="space-y-3 mt-3">
                <p class="text-xs text-neutral-500">
                    <span class="font-medium">{{ previews.length }}</span> foto{{ previews.length !== 1 ? 's' : '' }}
                    nueva{{
                        previews.length !== 1 ? 's' : '' }}
                    — hacé clic en una para definir la portada.
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <div v-for="(preview, index) in previews" :key="index"
                        class="relative group rounded-xl overflow-hidden border-2 cursor-pointer transition-all duration-200"
                        :class="form.imagen_principal_index === index
                            ? 'border-primary ring-2 ring-primary/30'
                            : 'border-neutral-200 hover:border-neutral-400'"
                        @click="form.imagen_principal_index = index">
                        <img :src="preview" class="w-full aspect-4/3 object-cover" />
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors" />
                        <span v-if="form.imagen_principal_index === index"
                            class="absolute bottom-0 left-0 right-0 text-center text-[10px] font-semibold bg-primary text-white py-1">
                            Portada
                        </span>
                        <button type="button"
                            class="absolute top-1.5 right-1.5 w-6 h-6 flex items-center justify-center rounded-full bg-black/60 text-white opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600"
                            @click.stop="eliminarImagen(index)">
                            <X class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>

            <FormMessage :message="form.errors.imagenes" />
        </FormSection>

        <div class="flex items-center gap-3">
            <button type="submit" :disabled="form.processing"
                class="h-11 px-8 rounded-md bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                {{ form.processing ? 'Guardando...' : submitLabel }}
            </button>

            <Link v-if="cancelHref" :href="cancelHref"
                class="h-11 px-6 inline-flex items-center rounded-md border border-neutral-200 bg-white text-sm font-medium text-neutral-600 hover:bg-neutral-50 hover:text-neutral-800 transition-colors">
                Cancelar
            </Link>
        </div>

    </form>
</template>
