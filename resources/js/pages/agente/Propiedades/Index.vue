<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import PanelLayout from '@/layouts/PanelLayout.vue'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter, DialogClose } from '@/components/ui/dialog'
import { Plus, Search, Pencil, Trash2, PauseCircle, PlayCircle, LayoutDashboard, Home, MessageSquare } from 'lucide-vue-next'

interface Propiedad {
    id: number
    titulo: string
    tipo_operacion: 'Venta' | 'Alquiler'
    tipo_propiedad: string
    estado_propiedad: string
    ubicacion: { localidad: string; departamento: string; direccion: string } | null
    detalle_propiedad: { precio: number; nro_habitaciones: number; nro_banios: number; nro_garage: number; superficie_total: number } | null
    imagenes: { url: string; es_principal: boolean }[]
}

const props = defineProps<{ propiedades: Propiedad[] }>()

const navLinks = [
    { label: 'Dashboard',           href: '/agente/dashboard',   icon: LayoutDashboard },
    { label: 'Mis Propiedades',     href: '/agente/propiedades', icon: Home },
    { label: 'Consultas Recibidas', href: '/agente/consultas',   icon: MessageSquare },
]

const busqueda    = ref('')
const filtroEstado = ref('todos')
const filtroOp    = ref('todos')
const filtroTipo  = ref('todos')

const propiedadesFiltradas = computed(() => {
    return props.propiedades.filter(p => {
        const q = busqueda.value.toLowerCase()
        const okBusqueda = !q || p.titulo.toLowerCase().includes(q) || (p.ubicacion?.localidad ?? '').toLowerCase().includes(q)
        const okEstado   = filtroEstado.value === 'todos' || p.estado_propiedad === filtroEstado.value
        const okOp       = filtroOp.value === 'todos' || p.tipo_operacion === filtroOp.value
        const okTipo     = filtroTipo.value === 'todos' || p.tipo_propiedad === filtroTipo.value
        return okBusqueda && okEstado && okOp && okTipo
    })
})

function imagenPrincipal(imagenes: Propiedad['imagenes']): string | null {
    return imagenes.find(i => i.es_principal)?.url ?? imagenes[0]?.url ?? null
}

function formatPrecio(precio: number, op: string): string {
    const n = new Intl.NumberFormat('es-UY').format(precio)
    return op === 'Alquiler' ? `USD ${n}/mes` : `USD ${n}`
}

function estadoClass(estado: string): string {
    const map: Record<string, string> = {
        Disponible: 'bg-green-100 text-green-700 border-green-200',
        Reservada:  'bg-yellow-100 text-yellow-700 border-yellow-200',
        Vendida:    'bg-blue-100 text-blue-700 border-blue-200',
        Alquilada:  'bg-purple-100 text-purple-700 border-purple-200',
        Pausada:    'bg-neutral-100 text-neutral-500 border-neutral-200',
    }
    return map[estado] ?? 'bg-neutral-100 text-neutral-500 border-neutral-200'
}

function toggleEstado(p: Propiedad) {
    const nuevo = p.estado_propiedad === 'Pausada' ? 'Disponible' : 'Pausada'
    router.patch(`/agente/propiedades/${p.id}`, { estado_propiedad: nuevo } as any)
}

const propiedadAEliminar = ref<number | null>(null)

function eliminar(id: number) {
    propiedadAEliminar.value = id
}

function confirmarEliminar() {
    if (propiedadAEliminar.value === null) return
    router.delete(`/agente/propiedades/${propiedadAEliminar.value}`)
    propiedadAEliminar.value = null
}
</script>

<template>
    <PanelLayout :nav-links="navLinks">

        <!-- Encabezado -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Mis Propiedades</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ propiedades.length }} propiedad{{ propiedades.length !== 1 ? 'es' : '' }}
                </p>
            </div>
            <Button as-child>
                <Link href="/agente/propiedades/crear" class="inline-flex items-center gap-2">
                    <Plus class="w-4 h-4" />
                    Nueva propiedad
                </Link>
            </Button>
        </div>

        <!-- Estado vacío -->
        <div v-if="propiedades.length === 0"
            class="rounded-xl border border-dashed border-gray-300 bg-white p-16 text-center">
            <p class="mb-4 text-sm text-gray-400">Todavía no publicaste ninguna propiedad.</p>
            <Button as-child size="sm">
                <Link href="/agente/propiedades/crear" class="inline-flex items-center gap-2">
                    <Plus class="w-4 h-4" />
                    Crear primera propiedad
                </Link>
            </Button>
        </div>

        <template v-else>
            <!-- Filtros -->
            <div class="mb-5 flex flex-wrap gap-3">
                <div class="relative min-w-48 flex-1">
                    <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-neutral-400" />
                    <input
                        v-model="busqueda"
                        type="text"
                        placeholder="Buscar por título o localidad..."
                        class="h-9 w-full rounded-md border border-input bg-background pl-9 pr-3 text-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/50 focus:border-ring"
                    />
                </div>

                <Select v-model="filtroEstado">
                    <SelectTrigger class="w-40 data-[size=default]:h-9">
                        <SelectValue placeholder="Estado" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="todos">Todos los estados</SelectItem>
                        <SelectItem value="Disponible">Disponible</SelectItem>
                        <SelectItem value="Reservada">Reservada</SelectItem>
                        <SelectItem value="Vendida">Vendida</SelectItem>
                        <SelectItem value="Alquilada">Alquilada</SelectItem>
                        <SelectItem value="Pausada">Pausada</SelectItem>
                    </SelectContent>
                </Select>

                <Select v-model="filtroOp">
                    <SelectTrigger class="w-36 data-[size=default]:h-9">
                        <SelectValue placeholder="Operación" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="todos">Todas</SelectItem>
                        <SelectItem value="Venta">Venta</SelectItem>
                        <SelectItem value="Alquiler">Alquiler</SelectItem>
                    </SelectContent>
                </Select>

                <Select v-model="filtroTipo">
                    <SelectTrigger class="w-36 data-[size=default]:h-9">
                        <SelectValue placeholder="Tipo" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="todos">Todos los tipos</SelectItem>
                        <SelectItem value="Casa">Casa</SelectItem>
                        <SelectItem value="Apartamento">Apartamento</SelectItem>
                        <SelectItem value="Terreno">Terreno</SelectItem>
                        <SelectItem value="Local">Local</SelectItem>
                        <SelectItem value="Oficina">Oficina</SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <!-- Tabla -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-200 bg-neutral-50 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                            <th class="px-4 py-3 text-left">Propiedad</th>
                            <th class="hidden px-4 py-3 text-left sm:table-cell">Operación</th>
                            <th class="hidden px-4 py-3 text-left md:table-cell">Ubicación</th>
                            <th class="hidden px-4 py-3 text-left lg:table-cell">Precio</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        <tr v-for="p in propiedadesFiltradas" :key="p.id" class="transition-colors hover:bg-neutral-50">

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-14 shrink-0 overflow-hidden rounded-md bg-neutral-100">
                                        <img
                                            v-if="imagenPrincipal(p.imagenes)"
                                            :src="imagenPrincipal(p.imagenes)!"
                                            :alt="p.titulo"
                                            class="h-full w-full object-cover"
                                        />
                                        <div v-else class="flex h-full w-full items-center justify-center text-neutral-300">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75L12 3l9 6.75V21H3V9.75z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="max-w-48 truncate font-medium text-neutral-800">{{ p.titulo }}</p>
                                        <p class="mt-0.5 text-xs text-neutral-400">{{ p.tipo_propiedad }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="hidden px-4 py-3 sm:table-cell">
                                <Badge :class="p.tipo_operacion === 'Venta'
                                    ? 'bg-blue-600 text-white border-transparent'
                                    : 'bg-emerald-600 text-white border-transparent'">
                                    {{ p.tipo_operacion }}
                                </Badge>
                            </td>

                            <td class="hidden px-4 py-3 text-neutral-500 md:table-cell">
                                {{ p.ubicacion?.localidad ?? '—' }}, {{ p.ubicacion?.departamento ?? '' }}
                            </td>

                            <td class="hidden px-4 py-3 font-medium text-neutral-700 lg:table-cell">
                                {{ p.detalle_propiedad ? formatPrecio(p.detalle_propiedad.precio, p.tipo_operacion) : '—' }}
                            </td>

                            <td class="px-4 py-3">
                                <Badge :class="estadoClass(p.estado_propiedad)">
                                    {{ p.estado_propiedad }}
                                </Badge>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <Link
                                        :href="`/agente/propiedades/${p.id}/editar`"
                                        class="flex h-8 w-8 items-center justify-center rounded-md text-neutral-400 transition-colors hover:bg-neutral-100 hover:text-neutral-700"
                                        title="Editar"
                                    >
                                        <Pencil class="h-4 w-4" />
                                    </Link>

                                    <button
                                        type="button"
                                        class="flex h-8 w-8 items-center justify-center rounded-md transition-colors"
                                        :class="p.estado_propiedad === 'Pausada'
                                            ? 'text-amber-500 hover:bg-amber-50 hover:text-amber-700'
                                            : 'text-neutral-400 hover:bg-amber-50 hover:text-amber-600'"
                                        :title="p.estado_propiedad === 'Pausada' ? 'Activar' : 'Pausar'"
                                        @click="toggleEstado(p)"
                                    >
                                        <PlayCircle  v-if="p.estado_propiedad === 'Pausada'" class="h-4 w-4" />
                                        <PauseCircle v-else class="h-4 w-4" />
                                    </button>

                                    <button
                                        type="button"
                                        class="flex h-8 w-8 items-center justify-center rounded-md text-neutral-400 transition-colors hover:bg-red-50 hover:text-red-600"
                                        title="Eliminar"
                                        @click="eliminar(p.id)"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>

                        </tr>
                    </tbody>
                </table>

                <div v-if="propiedadesFiltradas.length === 0" class="py-16 text-center text-sm text-neutral-400">
                    No hay propiedades que coincidan con los filtros.
                </div>
            </div>

            <p v-if="propiedadesFiltradas.length !== propiedades.length" class="mt-3 text-right text-xs text-neutral-400">
                Mostrando {{ propiedadesFiltradas.length }} de {{ propiedades.length }} propiedades
            </p>
        </template>

        <!-- Dialog confirmar eliminación -->
        <Dialog :open="propiedadAEliminar !== null" @update:open="(v) => { if (!v) propiedadAEliminar = null }">
            <DialogContent :show-close-button="false" class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Eliminar propiedad</DialogTitle>
                    <DialogDescription>
                        Esta acción es permanente y no se puede deshacer. ¿Estás seguro que querés eliminar esta propiedad?
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="gap-4 sm:gap-2">
                    <DialogClose as-child>
                        <Button variant="outline">Cancelar</Button>
                    </DialogClose>
                    <Button variant="destructive" @click="confirmarEliminar">
                        Sí, eliminar
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

    </PanelLayout>
</template>