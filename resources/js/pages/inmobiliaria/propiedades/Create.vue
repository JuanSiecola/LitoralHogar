<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import PanelLayout from '@/layouts/PanelLayout.vue'
import { useInmobiliariaNav } from '@/composables/useInmobiliariaNav'
import { store } from '@/routes/inmobiliaria/propiedades'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Checkbox } from '@/components/ui/checkbox'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { MapPin, Home, FileText, DollarSign, PawPrint } from 'lucide-vue-next'

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
})

function submit() {
    form.post(store.url())
}

</script>

<template>
    <PanelLayout :navLinks="navLinks">

        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-gray-900">Crear Propiedad</h1>
            <p class="text-sm text-gray-500 mt-1">Completá todos los datos para publicar la propiedad.</p>
        </div>

        <form @submit.prevent="submit">
            <div class="max-w-7xl space-y-8">

                <!-- Información básica -->
                <div class="rounded-xl border border-neutral-200 bg-white shadow-sm overflow-hidden">
                    <div class="flex items-center gap-2 px-6 py-4 border-b border-neutral-100 bg-neutral-50">
                        <FileText class="w-4 h-4 text-neutral-400" />
                        <h2 class="text-sm font-semibold text-neutral-700">Información básica</h2>
                    </div>
                    <div class="p-6 grid gap-4">

                        <Input id="titulo" label="Título" required v-model="form.titulo" :error="form.errors.titulo"
                            placeholder="Ej: Casa amplia en zona céntrica" />

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="grid gap-1.5">
                                <label class="text-sm font-medium text-neutral-700">
                                    Tipo <span class="ml-0.5 text-danger">*</span>
                                </label>
                                <Select v-model="form.tipo_propiedad">
                                    <SelectTrigger class="w-full h-11" :aria-invalid="!!form.errors.tipo_propiedad">
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
                                <p v-if="form.errors.tipo_propiedad" class="text-xs font-medium text-danger">{{
                                    form.errors.tipo_propiedad }}</p>
                            </div>

                            <div class="grid gap-1.5">
                                <label class="text-sm font-medium text-neutral-700">
                                    Operación <span class="ml-0.5 text-danger">*</span>
                                </label>
                                <Select v-model="form.tipo_operacion">
                                    <SelectTrigger class="w-full h-11" :aria-invalid="!!form.errors.tipo_operacion">
                                        <SelectValue placeholder="Seleccioná" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="Venta">Venta</SelectItem>
                                        <SelectItem value="Alquiler">Alquiler</SelectItem>
                                    </SelectContent>
                                </Select>
                                <p v-if="form.errors.tipo_operacion" class="text-xs font-medium text-danger">{{
                                    form.errors.tipo_operacion }}</p>
                            </div>

                            <div class="grid gap-1.5">
                                <label class="text-sm font-medium text-neutral-700">
                                    Estado <span class="ml-0.5 text-danger">*</span>
                                </label>
                                <Select v-model="form.estado_propiedad">
                                    <SelectTrigger class="w-full h-11" :aria-invalid="!!form.errors.estado_propiedad">
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
                                <p v-if="form.errors.estado_propiedad" class="text-xs font-medium text-danger">{{
                                    form.errors.estado_propiedad }}</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="rounded-xl border border-neutral-200 bg-white shadow-sm overflow-hidden">
                    <div class="flex items-center gap-2 px-6 py-4 border-b border-neutral-100 bg-neutral-50">
                        <MapPin class="w-4 h-4 text-neutral-400" />
                        <h2 class="text-sm font-semibold text-neutral-700">Ubicación</h2>
                    </div>
                    <div class="p-6 grid gap-4">

                        <Input id="direccion" label="Dirección" required v-model="form.direccion"
                            :error="form.errors.direccion" placeholder="Ej: Av. Colón 1234" />

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <Input id="ciudad" label="Ciudad" required v-model="form.ciudad" :error="form.errors.ciudad"
                                placeholder="Ej: Córdoba" />
                            <Input id="departamento" label="Departamento" required v-model="form.departamento"
                                :error="form.errors.departamento" placeholder="Ej: Capital" />
                        </div>

                    </div>
                </div>

                <div class="rounded-xl border border-neutral-200 bg-white shadow-sm overflow-hidden">
                    <div class="flex items-center gap-2 px-6 py-4 border-b border-neutral-100 bg-neutral-50">
                        <Home class="w-4 h-4 text-neutral-400" />
                        <h2 class="text-sm font-semibold text-neutral-700">Detalle de la propiedad</h2>
                    </div>
                    <div class="p-6 grid gap-4">

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <Input id="nro_habitaciones" label="Habitaciones" required type="number"
                                :min="0" 
                                :max="20"
                                v-model="form.nro_habitaciones" 
                                :error="form.errors.nro_habitaciones" />
                            <Input id="nro_banios" label="Baños" required type="number"
                                :min="0" :max="10"
                                v-model="form.nro_banios" :error="form.errors.nro_banios" />
                            <Input id="nro_garage" label="Garage" required type="number"
                                :min="0" :max="5"
                                v-model="form.nro_garage" :error="form.errors.nro_garage" />
                            <Input id="pisos" label="Pisos" required type="number"
                                :min="1"
                                v-model="form.pisos" :error="form.errors.pisos" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <Input id="superficie_total" label="Superficie (m²)" required
                                placeholder="240" :min="1"
                                v-model="form.superficie_total" :error="form.errors.superficie_total" />
                            <Input id="precio" label="Precio ($UYU)" required 
                                placeholder="200000" :min="0"
                                v-model="form.precio" :error="form.errors.precio" />
                            <Input id="anio_construccion" label="Año construcción" required type="number"
                                :min="1900" :max="new Date().getFullYear()"
                                v-model="form.anio_construccion" :error="form.errors.anio_construccion" />
                        </div>

                        <div class="grid gap-1.5">
                            <label class="text-sm font-medium text-neutral-700">
                                Estado de construcción <span class="ml-0.5 text-danger">*</span>
                            </label>
                            <Select v-model="form.estado_construccion">
                                <SelectTrigger class="w-full h-11" :aria-invalid="!!form.errors.estado_construccion">
                                    <SelectValue placeholder="Seleccioná" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="Nuevo">Nuevo</SelectItem>
                                    <SelectItem value="Usado">Usado</SelectItem>
                                    <SelectItem value="Reciclar">Reciclar</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.estado_construccion" class="text-xs font-medium text-danger">{{
                                form.errors.estado_construccion }}</p>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <Checkbox id="acepta_mascotas" :checked="form.acepta_mascotas"
                                @update:checked="form.acepta_mascotas = $event" />
                            <Label for="acepta_mascotas" class="cursor-pointer text-sm text-neutral-700">
                                <PawPrint class="w-3.5 h-3.5 text-neutral-400" />
                                Acepta mascotas
                            </Label>
                        </div>

                    </div>
                </div>

                <div v-if="form.tipo_operacion === 'Alquiler'"
                    class="rounded-xl border border-neutral-200 bg-white shadow-sm overflow-hidden">
                    <div class="flex items-center gap-2 px-6 py-4 border-b border-neutral-100 bg-neutral-50">
                        <DollarSign class="w-4 h-4 text-neutral-400" />
                        <h2 class="text-sm font-semibold text-neutral-700">Condiciones del alquiler</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <Input id="deposito" label="Depósito" type="number" placeholder="0" v-model="form.deposito"
                            :error="form.errors.deposito" />
                        <Input id="cant_meses_deposito" label="Meses de depósito" type="number" placeholder="1"
                            v-model="form.cant_meses_deposito" :error="form.errors.cant_meses_deposito" />
                        <Input id="expensas" label="Expensas" type="number" placeholder="0" v-model="form.expensas"
                            :error="form.errors.expensas" />
                    </div>
                </div>

                <div class="flex justify-end pb-6">
                    <button type="submit" :disabled="form.processing"
                        class="h-11 px-8 rounded-md bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        {{ form.processing ? 'Guardando...' : 'Publicar propiedad' }}
                    </button>
                </div>

            </div>
        </form>

    </PanelLayout>
</template>
