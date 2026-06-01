<script setup lang="ts">
import { ref, watch, onMounted, onBeforeUnmount, useTemplateRef, computed } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import iconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png'
import iconUrl from 'leaflet/dist/images/marker-icon.png'
import shadowUrl from 'leaflet/dist/images/marker-shadow.png'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import InputError from '@/components/InputError.vue'
import { Loader2, MapPin, Search } from 'lucide-vue-next'

// Fix iconos rotos de Leaflet con Vite
delete (L.Icon.Default.prototype as any)._getIconUrl
L.Icon.Default.mergeOptions({
    iconRetinaUrl,
    iconUrl,
    shadowUrl,
})

interface Departamento {
    id: number
    nombre: string
}

interface Localidad {
    id: number
    nombre: string
}

interface UbicacionData {
    direccion: string
    departamento_id: number | null
    localidad_id: number | null
    latitud: number | null
    longitud: number | null
}

interface UbicacionErrors {
    direccion?: string
    departamento_id?: string
    localidad_id?: string
    latitud?: string
    longitud?: string
}

const props = defineProps<{
    modelValue: UbicacionData
    departamentos: Departamento[]
    errors?: UbicacionErrors
}>()

const emit = defineEmits<{
    'update:modelValue': [value: UbicacionData]
}>()

const localidades = ref<Localidad[]>([])
const cargandoLocalidades = ref(false)
const geocodificando = ref(false)
const errorGeocoding = ref<string | null>(null)
let debounceTimer: ReturnType<typeof setTimeout> | null = null
const mapaRef = useTemplateRef<HTMLDivElement>('mapaRef')
let lastGeocodeQuery: string | null = null

const departamentoNombre = computed(() => {
    return props.departamentos.find(d => d.id === props.modelValue.departamento_id)?.nombre ?? ''
})

const localidadNombre = computed(() => {
    return localidades.value.find(l => l.id === props.modelValue.localidad_id)?.nombre ?? ''
})

// Centro de Uruguay para zoom inicial
const URUGUAY_CENTER: [number, number] = [-32.5228, -55.7658]
const URUGUAY_ZOOM = 7

let mapa: L.Map | null = null
let marker: L.Marker | null = null
let resizeObserver: ResizeObserver | null = null

function actualizar<K extends keyof UbicacionData>(campo: K, valor: UbicacionData[K]) {
    emit('update:modelValue', { ...props.modelValue, [campo]: valor })
}

async function cargarLocalidades(departamentoId: number) {
    cargandoLocalidades.value = true
    try {
        const res = await fetch(`/departamentos/${departamentoId}/localidades`, {
            headers: { Accept: 'application/json' },
        })
        const json = await res.json()
        localidades.value = json.data
    } catch (e) {
        console.error('Error cargando localidades:', e)
        localidades.value = []
    } finally {
        cargandoLocalidades.value = false
    }
}

async function geocodificar() {
    const { direccion, departamento_id, localidad_id } = props.modelValue
    if (!direccion || !departamento_id || !localidad_id) return

    const query = `${direccion}|${departamento_id}|${localidad_id}`
    if (query === lastGeocodeQuery) return
    lastGeocodeQuery = query

    geocodificando.value = true
    errorGeocoding.value = null

    try {
        const params = new URLSearchParams({
            direccion,
            localidad: localidadNombre.value,
            departamento: departamentoNombre.value,
        })

        const res = await fetch(`/geocode?${params}`, {
            headers: { Accept: 'application/json' },
        })
        const json = await res.json()

        if (json.encontrado) {
            crearMarker(json.lat, json.lng)
            mapa?.setView([json.lat, json.lng], 17)
            emit('update:modelValue', {
                ...props.modelValue,
                latitud: Number(json.lat.toFixed(7)),
                longitud: Number(json.lng.toFixed(7)),
            })
        } else {
            errorGeocoding.value = 'No pudimos ubicar esa dirección. Marcala manualmente en el mapa.'
        }
    } catch (e) {
        console.error('Error geocoding:', e)
        errorGeocoding.value = 'Error de conexión. Marcá la ubicación manualmente.'
    } finally {
        geocodificando.value = false
    }
}

watch(
    () => props.modelValue.departamento_id,
    (nuevoId, viejoId) => {
        if (nuevoId && nuevoId === viejoId) return
        if (nuevoId) {
            cargarLocalidades(nuevoId)
            if (viejoId !== undefined) {
                actualizar('localidad_id', null)
            }
        } else {
            localidades.value = []
            actualizar('localidad_id', null)
        }
    },
    { immediate: true }
)

watch(
    () => [props.modelValue.direccion, props.modelValue.departamento_id, props.modelValue.localidad_id],
    () => {
        if (debounceTimer) clearTimeout(debounceTimer)
        debounceTimer = setTimeout(() => geocodificar(), 600)
    }
)

function crearMarker(lat: number, lng: number) {
    if (!mapa) return
    if (marker) {
        marker.setLatLng([lat, lng])
        return
    }
    marker = L.marker([lat, lng], { draggable: true }).addTo(mapa)
    marker.on('dragend', () => {
        const pos = marker!.getLatLng()
        emit('update:modelValue', {
            ...props.modelValue,
            latitud: Number(pos.lat.toFixed(7)),
            longitud: Number(pos.lng.toFixed(7)),
        })
    })
}

onMounted(() => {
    if (!mapaRef.value) return

    mapa = L.map(mapaRef.value).setView(URUGUAY_CENTER, URUGUAY_ZOOM)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(mapa)

    if (mapaRef.value) {
        resizeObserver = new ResizeObserver(() => mapa?.invalidateSize())
        resizeObserver.observe(mapaRef.value)
    }

    if (props.modelValue.latitud && props.modelValue.longitud) {
        mapa.setView([props.modelValue.latitud, props.modelValue.longitud], 16)
        crearMarker(props.modelValue.latitud, props.modelValue.longitud)
    }

    mapa.on('click', (e: L.LeafletMouseEvent) => {
        const { lat, lng } = e.latlng
        crearMarker(lat, lng)
        emit('update:modelValue', {
            ...props.modelValue,
            latitud: Number(lat.toFixed(7)),
            longitud: Number(lng.toFixed(7)),
        })
    })
})

onBeforeUnmount(() => {
    if (debounceTimer) clearTimeout(debounceTimer)
    resizeObserver?.disconnect()
    mapa?.remove()
    mapa = null
    marker = null
})
</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-1.5">
            <Input id="direccion" type="text" label="Dirección" required placeholder="Ej: Av. Italia 1234"
                :model-value="modelValue.direccion" @update:model-value="actualizar('direccion', String($event))" />
            <InputError :message="errors?.direccion" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="flex flex-col gap-1.5">
                <Label for="departamento_id">Departamento</Label>
                <Select :model-value="modelValue.departamento_id?.toString() ?? ''"
                    @update:model-value="actualizar('departamento_id', $event ? Number($event) : null)">
                    <SelectTrigger id="departamento_id" class="w-full">
                        <SelectValue placeholder="Seleccione un departamento" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="depto in departamentos" :key="depto.id" :value="depto.id.toString()">
                            {{ depto.nombre }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="errors?.departamento_id" />
            </div>

            <div class="flex flex-col gap-1.5">
                <Label for="localidad_id" class="flex items-center gap-2">
                    Localidad
                    <Loader2 v-if="cargandoLocalidades" class="w-3.5 h-3.5 animate-spin text-neutral-400" />
                </Label>
                <Select :disabled="!modelValue.departamento_id || cargandoLocalidades"
                    :model-value="modelValue.localidad_id?.toString() ?? ''"
                    @update:model-value="actualizar('localidad_id', $event ? Number($event) : null)">
                    <SelectTrigger id="localidad_id" class="w-full">
                        <SelectValue :placeholder="!modelValue.departamento_id
                            ? 'Primero elige departamento'
                            : cargandoLocalidades
                                ? 'Cargando...'
                                : 'Seleccione una localidad'
                            " />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="loc in localidades" :key="loc.id" :value="loc.id.toString()">
                            {{ loc.nombre }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="errors?.localidad_id" />
            </div>
        </div>

        <div class="flex flex-col gap-1.5">
            <Label class="flex items-center gap-2">
                <MapPin class="w-3.5 h-3.5 text-neutral-400" />
                Ubicación en el mapa
                <span v-if="geocodificando" class="inline-flex items-center gap-1 text-xs text-neutral-400 font-normal">
                    <Search class="w-3 h-3 animate-pulse" />
                    Buscando dirección...
                </span>
            </Label>
            <p class="text-xs text-neutral-500">
                El marcador se ubica automáticamente al completar dirección, departamento y localidad. Podés arrastrarlo
                para ajustar.
            </p>
            <p v-if="errorGeocoding" class="text-xs text-amber-600">
                {{ errorGeocoding }}
            </p>
            <div ref="mapaRef" class="w-full h-80 rounded-lg border border-neutral-200 overflow-hidden z-0" />
            <div class="flex items-center gap-2 text-xs text-neutral-500 mt-1">
                <span class="font-medium">Coordenadas:</span>
                <span v-if="modelValue.latitud && modelValue.longitud" class="font-mono">
                    {{ modelValue.latitud.toFixed(7) }}, {{ modelValue.longitud.toFixed(7) }}
                </span>
                <span v-else class="italic text-neutral-400">
                    Aún no marcaste el punto en el mapa
                </span>
            </div>
            <InputError :message="errors?.latitud" />
            <InputError :message="errors?.longitud" />
        </div>
    </div>
</template>