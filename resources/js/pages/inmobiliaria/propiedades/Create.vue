<script setup lang="ts">
import { computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import PanelLayout from '@/layouts/PanelLayout.vue'
import { useInmobiliariaNav } from '@/composables/useInmobiliariaNav'
import { useImagenes } from '@/composables/useImagenes'
import { store } from '@/routes/inmobiliaria/propiedades'
import { Input } from '@/components/ui/input'
import { Checkbox } from '@/components/ui/checkbox'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/components/ui/tabs'
import { FormMessage } from '@/components/ui/form'
import { FileText, Home, MapPin, ImageIcon, Sparkles, PawPrint, DollarSign, X } from 'lucide-vue-next'

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
    ciudad: '',
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

const tabErrors = computed(() => ({
    datos:      !!(form.errors.titulo || form.errors.tipo_propiedad || form.errors.tipo_operacion || form.errors.estado_propiedad),
    detalle:    !!(form.errors.precio || form.errors.superficie_total || form.errors.nro_habitaciones || form.errors.estado_construccion),
    ubicacion:  !!(form.errors.direccion || form.errors.ciudad || form.errors.departamento),
    fotos:      !!(form.errors.imagenes),
    amenidades: !!(form.errors.amenidades),
}))

function submit() {
    form.post(store.url(), { forceFormData: true })
}
</script>

<template>
    <PanelLayout :navLinks="navLinks">

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Crear Propiedad</h1>
            <p class="text-sm text-gray-500 mt-1">Completá todos los datos para publicar la propiedad.</p>
        </div>

        <form @submit.prevent="submit">
            <Tabs default-value="datos" class="w-full">

                <TabsList class="w-full h-auto p-0 grid grid-cols-5 mb-6 bg-primary/10 border border-primary/20 overflow-hidden">
                    <TabsTrigger value="datos" class="flex items-center justify-center gap-1.5 py-3 text-xs sm:text-sm h-full rounded-none data-[state=active]:bg-primary data-[state=active]:text-primary-foreground data-[state=active]:shadow-none">
                        <FileText class="w-3.5 h-3.5 shrink-0" />
                        <span>Datos</span>
                        <span v-if="tabErrors.datos" class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0" />
                    </TabsTrigger>
                    <TabsTrigger value="detalle" class="flex items-center justify-center gap-1.5 py-3 text-xs sm:text-sm h-full rounded-none data-[state=active]:bg-primary data-[state=active]:text-primary-foreground data-[state=active]:shadow-none">
                        <Home class="w-3.5 h-3.5 shrink-0" />
                        <span>Detalle</span>
                        <span v-if="tabErrors.detalle" class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0" />
                    </TabsTrigger>
                    <TabsTrigger value="ubicacion" class="flex items-center justify-center gap-1.5 py-3 text-xs sm:text-sm h-full rounded-none data-[state=active]:bg-primary data-[state=active]:text-primary-foreground data-[state=active]:shadow-none">
                        <MapPin class="w-3.5 h-3.5 shrink-0" />
                        <span>Ubicación</span>
                        <span v-if="tabErrors.ubicacion" class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0" />
                    </TabsTrigger>
                    <TabsTrigger value="fotos" class="flex items-center justify-center gap-1.5 py-3 text-xs sm:text-sm h-full rounded-none data-[state=active]:bg-primary data-[state=active]:text-primary-foreground data-[state=active]:shadow-none">
                        <ImageIcon class="w-3.5 h-3.5 shrink-0" />
                        <span>Fotos</span>
                        <span v-if="tabErrors.fotos" class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0" />
                    </TabsTrigger>
                    <TabsTrigger value="amenidades" class="flex items-center justify-center gap-1.5 py-3 text-xs sm:text-sm h-full rounded-none data-[state=active]:bg-primary data-[state=active]:text-primary-foreground data-[state=active]:shadow-none">
                        <Sparkles class="w-3.5 h-3.5 shrink-0" />
                        <span>Amenidades</span>
                        <span v-if="tabErrors.amenidades" class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0" />
                    </TabsTrigger>
                </TabsList>

                <TabsContent value="datos">
                    <div class="rounded-xl border border-neutral-200 bg-white shadow-sm p-6 space-y-5">
                        <div>
                            <h2 class="text-xl font-semibold text-neutral-700 mb-1">Información básica</h2>
                            <p class="text-sm text-neutral-400">Título, tipo y estado de la propiedad.</p>
                        </div>

                        <Input id="titulo" label="Título" required v-model="form.titulo"
                            :error="form.errors.titulo" placeholder="Ej: Casa amplia en zona céntrica" />

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="grid gap-1.5">
                                <label class="text-sm font-medium text-neutral-700">
                                    Tipo <span class="text-danger ml-0.5">*</span>
                                </label>
                                <Select v-model="form.tipo_propiedad">
                                    <SelectTrigger class="w-full data-[size=default]:h-11" :aria-invalid="!!form.errors.tipo_propiedad">
                                        <SelectValue placeholder="Seleccioná" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="Casa">Casa</SelectItem>
                                        <SelectItem value="Apartamento">Apartamento</SelectItem>
                                        <SelectItem value="Terreno">Terreno</SelectItem>
                                        <SelectItem value="Local">Local</SelectItem>
                                        <SelectItem value="Oficina">Oficina</SelectItem>
                                    </SelectContent>
                                </Select>
                                <FormMessage :message="form.errors.tipo_propiedad" />
                            </div>

                            <div class="grid gap-1.5">
                                <label class="text-sm font-medium text-neutral-700">
                                    Operación <span class="text-danger ml-0.5">*</span>
                                </label>
                                <Select v-model="form.tipo_operacion">
                                    <SelectTrigger class="w-full data-[size=default]:h-11" :aria-invalid="!!form.errors.tipo_operacion">
                                        <SelectValue placeholder="Seleccioná" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="Venta">Venta</SelectItem>
                                        <SelectItem value="Alquiler">Alquiler</SelectItem>
                                    </SelectContent>
                                </Select>
                                <FormMessage :message="form.errors.tipo_operacion" />
                            </div>

                            <div class="grid gap-1.5">
                                <label class="text-sm font-medium text-neutral-700">
                                    Estado <span class="text-danger ml-0.5">*</span>
                                </label>
                                <Select v-model="form.estado_propiedad">
                                    <SelectTrigger class="w-full data-[size=default]:h-11" :aria-invalid="!!form.errors.estado_propiedad">
                                        <SelectValue placeholder="Seleccioná" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="Disponible">Disponible</SelectItem>
                                        <SelectItem value="Reservada">Reservada</SelectItem>
                                        <SelectItem value="Vendida">Vendida</SelectItem>
                                        <SelectItem value="Alquilada">Alquilada</SelectItem>
                                        <SelectItem value="Pausada">Pausada</SelectItem>
                                    </SelectContent>
                                </Select>
                                <FormMessage :message="form.errors.estado_propiedad" />
                            </div>
                        </div>
                    </div>
                </TabsContent>

                <TabsContent value="detalle">
                    <div class="rounded-xl border border-neutral-200 bg-white shadow-sm p-6 space-y-5">
                        <div>
                            <h2 class="text-xl font-semibold text-neutral-700 mb-1">Detalle de la propiedad</h2>
                            <p class="text-sm text-neutral-400">Características físicas y precio.</p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <Input id="nro_habitaciones" label="Habitaciones" type="number" :min="0" :max="20"
                                v-model="form.nro_habitaciones" :error="form.errors.nro_habitaciones" />
                            <Input id="nro_banios" label="Baños" type="number" :min="0" :max="10"
                                v-model="form.nro_banios" :error="form.errors.nro_banios" />
                            <Input id="nro_garage" label="Garage" type="number" :min="0" :max="5"
                                v-model="form.nro_garage" :error="form.errors.nro_garage" />
                            <Input id="pisos" label="Pisos" type="number" :min="1"
                                v-model="form.pisos" :error="form.errors.pisos" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <Input id="superficie_total" label="Superficie (m²)" required placeholder="240" :min="1"
                                v-model="form.superficie_total" :error="form.errors.superficie_total" />
                            <Input id="precio" label="Precio (USD)" required placeholder="200000" :min="0"
                                v-model="form.precio" :error="form.errors.precio" />
                            <Input id="anio_construccion" label="Año construcción" type="number"
                                :min="1900" :max="new Date().getFullYear()"
                                v-model="form.anio_construccion" :error="form.errors.anio_construccion" />
                        </div>

                        <div class="grid gap-1.5">
                            <label class="text-sm font-medium text-neutral-700">
                                Estado de construcción <span class="text-danger ml-0.5">*</span>
                            </label>
                            <Select v-model="form.estado_construccion">
                                <SelectTrigger class="w-full data-[size=default]:h-11" :aria-invalid="!!form.errors.estado_construccion">
                                    <SelectValue placeholder="Seleccioná" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="Nuevo">Nuevo</SelectItem>
                                    <SelectItem value="Usado">Usado</SelectItem>
                                    <SelectItem value="Reciclar">Reciclar</SelectItem>
                                </SelectContent>
                            </Select>
                            <FormMessage :message="form.errors.estado_construccion" />
                        </div>

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

                        <div v-if="form.tipo_operacion === 'Alquiler'"
                            class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 space-y-4">
                            <div class="flex items-center gap-2">
                                <DollarSign class="w-4 h-4 text-neutral-400" />
                                <p class="text-sm font-semibold text-neutral-700">Condiciones del alquiler</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <Input id="deposito" label="Depósito" type="number" placeholder="0"
                                    v-model="form.deposito" :error="form.errors.deposito" />
                                <Input id="cant_meses_deposito" label="Meses de depósito" type="number" placeholder="1"
                                    v-model="form.cant_meses_deposito" :error="form.errors.cant_meses_deposito" />
                                <Input id="expensas" label="Expensas" type="number" placeholder="0"
                                    v-model="form.expensas" :error="form.errors.expensas" />
                            </div>
                        </div>
                        <p v-else class="text-xs text-neutral-400 italic">
                            Las condiciones de alquiler aparecen cuando seleccionás "Alquiler" en la pestaña Datos.
                        </p>
                    </div>
                </TabsContent>

                <TabsContent value="ubicacion">
                    <div class="rounded-xl border border-neutral-200 bg-white shadow-sm p-6 space-y-5">
                        <div>
                            <h2 class="text-xl font-semibold text-neutral-700 mb-1">Ubicación</h2>
                            <p class="text-sm text-neutral-400">Dirección y localización de la propiedad.</p>
                        </div>

                        <Input id="direccion" label="Dirección" required v-model="form.direccion"
                            :error="form.errors.direccion" placeholder="Ej: Av. 18 de Julio 1234" />

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <Input id="ciudad" label="Ciudad" required v-model="form.ciudad"
                                :error="form.errors.ciudad" placeholder="Ej: Montevideo" />
                            <Input id="departamento" label="Departamento" required v-model="form.departamento"
                                :error="form.errors.departamento" placeholder="Ej: Montevideo" />
                        </div>
                    </div>
                </TabsContent>

                <TabsContent value="fotos">
                    <div class="rounded-xl border border-neutral-200 bg-white shadow-sm p-6 space-y-5">
                        <div>
                            <h2 class="text-xl font-semibold text-neutral-700 mb-1">Fotos de la propiedad</h2>
                            <p class="text-sm text-neutral-400">
                                Hasta 20 imágenes. Hacé clic en una para marcarla como portada.
                            </p>
                        </div>

                        <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-neutral-300 rounded-xl cursor-pointer hover:border-primary hover:bg-primary/5 transition-colors group">
                            <ImageIcon class="w-8 h-8 text-neutral-300 group-hover:text-primary transition-colors mb-2" />
                            <span class="text-sm font-medium text-neutral-500 group-hover:text-primary transition-colors">
                                Hacé clic para agregar fotos
                            </span>
                            <span class="text-xs text-neutral-400 mt-1">JPG, PNG, WEBP — máx. 5 MB por imagen</span>
                            <input type="file" multiple accept="image/*" class="hidden" @change="onImagenesChange" />
                        </label>

                        <div v-if="previews.length" class="space-y-3">
                            <p class="text-xs text-neutral-500">
                                <span class="font-medium">{{ previews.length }}</span> foto{{ previews.length !== 1 ? 's' : '' }} agregada{{ previews.length !== 1 ? 's' : '' }}
                                — hacé clic en una para definir la portada.
                            </p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                <div
                                    v-for="(preview, index) in previews"
                                    :key="index"
                                    class="relative group rounded-xl overflow-hidden border-2 cursor-pointer transition-all duration-200"
                                    :class="form.imagen_principal_index === index
                                        ? 'border-primary ring-2 ring-primary/30'
                                        : 'border-neutral-200 hover:border-neutral-400'"
                                    @click="form.imagen_principal_index = index"
                                >
                                    <img :src="preview" class="w-full aspect-4/3 object-cover" />

                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors" />

                                    <!-- Badge portada -->
                                    <span
                                        v-if="form.imagen_principal_index === index"
                                        class="absolute bottom-0 left-0 right-0 text-center text-[10px] font-semibold bg-primary text-white py-1"
                                    >
                                        Portada
                                    </span>

                                    <!-- Botón eliminar -->
                                    <button
                                        type="button"
                                        class="absolute top-1.5 right-1.5 w-6 h-6 flex items-center justify-center rounded-full bg-black/60 text-white opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600"
                                        @click.stop="eliminarImagen(index)"
                                    >
                                        <X class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <FormMessage :message="form.errors.imagenes" />
                    </div>
                </TabsContent>

                <TabsContent value="amenidades">
                    <div class="rounded-xl border border-neutral-200 bg-white shadow-sm p-6 space-y-5">
                        <div>
                            <h2 class="text-xl font-semibold text-neutral-700 mb-1">Instalaciones</h2>
                            <p class="text-sm text-neutral-400">Seleccioná las comodidades que tiene la propiedad.</p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            <label
                                v-for="amenidad in amenidades"
                                :key="amenidad.id"
                                :for="`amenidad-${amenidad.id}`"
                                class="flex items-center gap-2.5 p-3 rounded-lg border border-neutral-200 cursor-pointer hover:bg-neutral-50 transition-colors"
                                :class="{ 'border-primary bg-primary/5': form.amenidades.includes(amenidad.id) }"
                            >
                                <Checkbox
                                    :id="`amenidad-${amenidad.id}`"
                                    :checked="form.amenidades.includes(amenidad.id)"
                                    @update:checked="(checked: boolean) => {
                                        if (checked) form.amenidades.push(amenidad.id)
                                        else form.amenidades = form.amenidades.filter(id => id !== amenidad.id)
                                    }"
                                />
                                <span class="text-sm text-neutral-700 select-none">{{ amenidad.nombre }}</span>
                            </label>
                        </div>

                        <FormMessage :message="form.errors.amenidades" />
                    </div>
                </TabsContent>

            </Tabs>

            <div class="flex justify-end mt-6 pb-6">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="h-11 px-8 rounded-md bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    {{ form.processing ? 'Guardando...' : 'Publicar propiedad' }}
                </button>
            </div>

        </form>

    </PanelLayout>
</template>
