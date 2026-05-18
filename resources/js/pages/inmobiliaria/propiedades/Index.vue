<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import PanelLayout from '@/layouts/PanelLayout.vue'
import { useInmobiliariaNav } from '@/composables/useInmobiliariaNav'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import {DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator} from '@/components/ui/dropdown-menu'
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter, DialogClose } from '@/components/ui/dialog'
import { create, edit, update, destroy } from '@/routes/inmobiliaria/propiedades'
import { Plus, Search, Pencil, Trash2, EllipsisVertical, PauseCircle, PlayCircle } from 'lucide-vue-next'

interface Propiedad {
    id: number
    titulo: string
    tipo_operacion: 'Venta' | 'Alquiler'
    tipo_propiedad: string
    estado_propiedad: string
    ubicacion: { ciudad: string; departamento: string; direccion: string } | null
    detalle_propiedad: { precio: number; nro_habitaciones: number; nro_banios: number; nro_garage: number; superficie_total: number } | null
    imagenes: { url: string; es_principal: boolean }[]
}

const props = defineProps<{ propiedades: Propiedad[] }>()

const navLinks = useInmobiliariaNav()

const busqueda      = ref('')
const filtroEstado  = ref('todos')
const filtroOp      = ref('todos')
const filtroTipo    = ref('todos')

const propiedadesFiltradas = computed(() => {
    return props.propiedades.filter(p => {
        const q = busqueda.value.toLowerCase()
        const okBusqueda  = !q || p.titulo.toLowerCase().includes(q) || (p.ubicacion?.ciudad ?? '').toLowerCase().includes(q)
        const okEstado    = filtroEstado.value === 'todos' || p.estado_propiedad === filtroEstado.value
        const okOp        = filtroOp.value === 'todos'    || p.tipo_operacion === filtroOp.value
        const okTipo      = filtroTipo.value === 'todos'  || p.tipo_propiedad === filtroTipo.value
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
    router.patch(update.url(p.id), { estado_propiedad: nuevo } as any)
}

const propiedadAEliminar = ref<number | null>(null)

function eliminar(id: number) {
    propiedadAEliminar.value = id
}

function confirmarEliminar() {
    if (propiedadAEliminar.value === null) return
    router.delete(destroy.url(propiedadAEliminar.value))
    propiedadAEliminar.value = null
}
</script>

<template>
    <PanelLayout :navLinks="navLinks">

        <!-- Encabezado -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Mis Propiedades</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ propiedades.length }} propiedad{{ propiedades.length !== 1 ? 'es' : '' }}
                </p>
            </div>
            <Button as-child>
                <Link :href="create.url()" class="inline-flex items-center gap-2">
                    <Plus class="w-4 h-4" />
                    Nueva propiedad
                </Link>
            </Button>
        </div>

        <!-- Estado vacío total -->
        <div v-if="propiedades.length === 0"
            class="rounded-xl border border-dashed border-gray-300 bg-white p-16 text-center">
            <p class="text-sm text-gray-400 mb-4">Todavía no publicaste ninguna propiedad.</p>
            <Button as-child size="sm">
                <Link :href="create.url()" class="inline-flex items-center gap-2">
                    <Plus class="w-4 h-4" />
                    Crear primera propiedad
                </Link>
            </Button>
        </div>

        <template v-else>
            <!-- Barra de filtros -->
            <div class="flex flex-wrap gap-3 mb-5">
                <!-- Búsqueda -->
                <div class="relative flex-1 min-w-48">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 pointer-events-none" />
                    <input
                        v-model="busqueda"
                        type="text"
                        placeholder="Buscar por título o ciudad..."
                        class="w-full h-9 pl-9 pr-3 rounded-md border border-input bg-background text-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/50 focus:border-ring"
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
            <div class="rounded-xl border border-neutral-200 bg-white shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-200 bg-neutral-50 text-xs font-semibold text-neutral-500 uppercase tracking-wide">
                            <th class="px-4 py-3 text-left">Propiedad</th>
                            <th class="px-4 py-3 text-left hidden sm:table-cell">Operación</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">Ubicación</th>
                            <th class="px-4 py-3 text-left hidden lg:table-cell">Precio</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        <tr
                            v-for="p in propiedadesFiltradas"
                            :key="p.id"
                            class="hover:bg-neutral-50 transition-colors"
                        >
                            <!-- Propiedad: imagen + título + tipo -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-10 rounded-md overflow-hidden bg-neutral-100 shrink-0">
                                        <img
                                            v-if="imagenPrincipal(p.imagenes)"
                                            :src="imagenPrincipal(p.imagenes)!"
                                            :alt="p.titulo"
                                            class="w-full h-full object-cover"
                                        />
                                        <div v-else class="w-full h-full flex items-center justify-center text-neutral-300">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M3 9.75L12 3l9 6.75V21H3V9.75z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-neutral-800 truncate max-w-48">{{ p.titulo }}</p>
                                        <p class="text-xs text-neutral-400 mt-0.5">{{ p.tipo_propiedad }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Operación -->
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <Badge
                                    :class="p.tipo_operacion === 'Venta'
                                        ? 'bg-blue-600 text-white border-transparent'
                                        : 'bg-emerald-600 text-white border-transparent'"
                                >
                                    {{ p.tipo_operacion }}
                                </Badge>
                            </td>

                            <!-- Ubicación -->
                            <td class="px-4 py-3 hidden md:table-cell text-neutral-500">
                                {{ p.ubicacion?.ciudad ?? '—' }},
                                {{ p.ubicacion?.departamento ?? '' }}
                            </td>

                            <!-- Precio -->
                            <td class="px-4 py-3 hidden lg:table-cell font-medium text-neutral-700">
                                {{ p.detalle_propiedad ? formatPrecio(p.detalle_propiedad.precio, p.tipo_operacion) : '—' }}
                            </td>

                            <!-- Estado -->
                            <td class="px-4 py-3">
                                <Badge :class="estadoClass(p.estado_propiedad)">
                                    {{ p.estado_propiedad }}
                                </Badge>
                            </td>

                            <!-- Acciones -->
                            <td class="px-4 py-3 text-right">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="icon" class="w-8 h-8">
                                            <EllipsisVertical class="w-4 h-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end" class="w-44">
                                        <DropdownMenuItem as-child>
                                            <Link :href="edit.url(p.id)" class="flex items-center gap-2 cursor-pointer">
                                                <Pencil class="w-3.5 h-3.5" />
                                                Editar
                                            </Link>
                                        </DropdownMenuItem>
                                        <DropdownMenuItem
                                            class="flex items-center gap-2 cursor-pointer"
                                            @click="toggleEstado(p)"
                                        >
                                            <PauseCircle v-if="p.estado_propiedad !== 'Pausada'" class="w-3.5 h-3.5" />
                                            <PlayCircle v-else class="w-3.5 h-3.5" />
                                            {{ p.estado_propiedad === 'Pausada' ? 'Activar' : 'Pausar' }}
                                        </DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem
                                            class="flex items-center gap-2 cursor-pointer text-destructive focus:text-destructive focus:bg-destructive/10"
                                            @click="eliminar(p.id)"
                                        >
                                            <Trash2 class="w-3.5 h-3.5" />
                                            Eliminar
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Sin resultados tras filtrar -->
                <div v-if="propiedadesFiltradas.length === 0"
                    class="py-16 text-center text-sm text-neutral-400">
                    No hay propiedades que coincidan con los filtros.
                </div>
            </div>

            <!-- Contador de resultados -->
            <p v-if="propiedadesFiltradas.length !== propiedades.length"
                class="mt-3 text-xs text-neutral-400 text-right">
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
                <DialogFooter class="gap-2 sm:gap-0">
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
