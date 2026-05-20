<script setup lang="ts">
import { computed, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import PanelLayout from '@/layouts/PanelLayout.vue'
import { useInmobiliariaNav } from '@/composables/useInmobiliariaNav'
import { useImagenes } from '@/composables/useImagenes'
import { store } from '@/routes/inmobiliaria/propiedades'
import { Input } from '@/components/ui/input'
import { Checkbox } from '@/components/ui/checkbox'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { FormMessage } from '@/components/ui/form'
import { FileText, Home, MapPin, ImageIcon, Sparkles, PawPrint, DollarSign, X } from 'lucide-vue-next'
import InputError from '@/components/InputError.vue'

interface Amenidad {
    id: number
    nombre: string
}

defineProps<{ amenidades: Amenidad[] }>()

const navLinks = useInmobiliariaNav()

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
})

const { previews, onImagenesChange, eliminarImagen } = useImagenes(form)

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

        <form @submit.prevent="submit">

            <section class="bg-card rounded-xl border border-border p-6 mb-5 shadow-card">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-border">
                    <div
                        class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <Home class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-foreground">Datos principales</h2>
                        <p class="text-sm text-muted-foreground">Completa los datos principales de la propiedad.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1.5">
                        <Input id="titulo" type="text" name="titulo" label="Titulo" required v-model="form.titulo"
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
            </section>

            <section class="bg-card rounded-xl border border-border p-6 mb-5 shadow-card">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-border">
                    <div
                        class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <MapPin class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-foreground">Ubicación</h2>
                        <p class="text-sm text-muted-foreground mt-0.5">Dirección y coordenadas</p>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1.5">
                        <Input id="direccion" type="text" label="Dirección" required placeholder="Ej: Av. Italia 1234"
                            v-model="form.direccion" />
                        <InputError :message="form.errors.direccion" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <Input id="localidad" type="text" label="Localidad" required v-model="form.localidad"
                                placeholder="Ej: Paysandú" />
                            <InputError :message="form.errors.localidad" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Input id="departamento" type="text" label="Departamento" required
                                v-model="form.departamento" placeholder="Ej: Paysandú" />
                            <InputError :message="form.errors.departamento" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <Input id="latitud" type="number" label="Latitud" v-model="form.latitud"
                                placeholder="-32.3" />
                            <InputError :message="form.errors.latitud" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Input id="longitud" type="number" label="Longitud" v-model="form.longitud"
                                placeholder="-58.0" />
                            <InputError :message="form.errors.longitud" />
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-card rounded-xl border border-border p-6 mb-5 shadow-card">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-border">
                    <div
                        class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <DollarSign class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-foreground">Detalles de la propiedad</h2>
                        <p class="text-sm text-muted-foreground">Características físicas y precio.</p>
                    </div>
                </div>
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
                        <Checkbox id="acepta_mascotas" :checked="form.acepta_mascotas"
                            @update:checked="(v: boolean) => form.acepta_mascotas = v" />
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
                    Las condiciones de alquiler aparecen cuando seleccionás "Alquiler" en la pestaña Datos.
                </p>
            </section>

            <section class="bg-card rounded-xl border border-border p-6 mb-5 shadow-card">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-border">
                    <div
                        class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <ImageIcon class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-foreground">Imágenes</h2>
                        <p class="text-sm text-muted-foreground mt-0.5">Fotos de la propiedad</p>
                    </div>
                </div>

                <label
                    class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-neutral-300 rounded-xl cursor-pointer hover:border-primary hover:bg-primary/5 transition-colors group">
                    <ImageIcon class="w-8 h-8 text-neutral-300 group-hover:text-primary transition-colors mb-2" />
                    <span class="text-sm font-medium text-neutral-500 group-hover:text-primary transition-colors">
                        Hacé clic para agregar fotos
                    </span>
                    <span class="text-xs text-neutral-400 mt-1">JPG, PNG, WEBP — máx. 5 MB por imagen</span>
                    <input type="file" multiple accept="image/*" class="hidden" @change="onImagenesChange" />
                </label>

                <div v-if="previews.length" class="space-y-3">
                    <p class="text-xs text-neutral-500">
                        <span class="font-medium">{{ previews.length }}</span> foto{{ previews.length !== 1 ? 's' : ''
                        }} agregada{{ previews.length !== 1 ? 's' : '' }}
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

                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>

                            <!-- Badge portada -->
                            <span v-if="form.imagen_principal_index === index"
                                class="absolute bottom-0 left-0 right-0 text-center text-[10px] font-semibold bg-primary text-white py-1">
                                Portada
                            </span>

                            <!-- Botón eliminar -->
                            <button type="button"
                                class="absolute top-1.5 right-1.5 w-6 h-6 flex items-center justify-center rounded-full bg-black/60 text-white opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600"
                                @click.stop="eliminarImagen(index)">
                                <X class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>

                <FormMessage :message="form.errors.imagenes" />
            </section>

            <button type="submit" :disabled="form.processing"
                class="h-11 px-8 rounded-md bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                {{ form.processing ? 'Guardando...' : 'Publicar propiedad' }}
            </button>

        </form>

    </PanelLayout>
</template>
