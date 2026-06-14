<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { formatPrecio } from '@/lib/currency';
import {
    DollarSign, Home, ArrowLeftRight, Bed, ShowerHead,
    Square, Car, Layers, CalendarDays, Hammer, PawPrint,
    Receipt, Landmark, Tag,
} from 'lucide-vue-next';

interface PropiedadComparada {
    id: number;
    titulo: string;
    tipo_operacion: 'Venta' | 'Alquiler';
    tipo_propiedad: string;
    estado_propiedad: string;
    precio: number | null;
    nro_habitaciones: number | null;
    nro_banios: number | null;
    nro_garage: number | null;
    superficie_total: number | null;
    pisos: number | null;
    anio_construccion: number | null;
    estado_construccion: string | null;
    acepta_mascotas: boolean | null;
    expensas: number | null;
    deposito: number | null;
    direccion: string | null;
    localidad: string | null;
    departamento: string | null;
    imagen_principal: string | null;
    amenidades: string[];
}

const props = defineProps<{ propiedades: PropiedadComparada[] }>();

const campos = [
    { label: 'Precio',              icon: DollarSign,    valor: (p) => p.precio ? formatPrecio(p.precio, p.tipo_operacion) : '-' },
    { label: 'Tipo de propiedad',   icon: Home,          valor: (p) => p.tipo_propiedad ?? '-' },
    { label: 'Operación',           icon: ArrowLeftRight,valor: (p) => p.tipo_operacion ?? '-' },
    { label: 'Habitaciones',        icon: Bed,           valor: (p) => p.nro_habitaciones ?? '-' },
    { label: 'Baños',               icon: ShowerHead,    valor: (p) => p.nro_banios ?? '-' },
    { label: 'Superficie',          icon: Square,        valor: (p) => p.superficie_total ? `${p.superficie_total} m²` : '-' },
    { label: 'Garage',              icon: Car,           valor: (p) => p.nro_garage ?? '-' },
    { label: 'Pisos',               icon: Layers,        valor: (p) => p.pisos ?? '-' },
    { label: 'Año de construcción', icon: CalendarDays,  valor: (p) => p.anio_construccion ?? '-' },
    { label: 'Estado construcción', icon: Hammer,        valor: (p) => p.estado_construccion ?? '-' },
    { label: 'Mascotas',            icon: PawPrint,      valor: (p) => p.acepta_mascotas === null ? '-' : p.acepta_mascotas ? 'Acepta' : 'No acepta' },
    { label: 'Expensas',            icon: Receipt,       valor: (p) => p.expensas ? `$ ${p.expensas}` : '-' },
    { label: 'Depósito',            icon: Landmark,      valor: (p) => p.deposito ? `$ ${p.deposito}` : '-' },
];
</script>

<template>
    <div class="mx-auto max-w-5xl px-4 py-10">

        <nav class="mb-6 flex items-center gap-2 text-sm text-muted-foreground">
            <Link href="/" class="hover:text-foreground">Inicio</Link>
            <span>/</span>
            <Link href="/propiedades" class="hover:text-foreground">Propiedades</Link>
            <span>/</span>
            <span class="text-foreground">Comparar</span>
        </nav>

        <h1 class="text-2xl font-bold text-foreground mb-8">Comparar propiedades</h1>

        <div class="overflow-x-auto rounded-xl border border-border">
            <table class="w-full border-collapse">

                <!-- Encabezados con imagen y título -->
                <thead>
                    <tr>
                        <th class="w-44 border-b border-r border-border bg-muted/50 p-4"></th>
                        <th
                            v-for="prop in propiedades"
                            :key="prop.id"
                            class="border-b border-r border-border p-4 text-center last:border-r-0"
                        >
                            <img
                                :src="prop.imagen_principal ?? '/img/placeholder.jpg'"
                                :alt="prop.titulo"
                                class="mx-auto mb-3 h-40 w-full rounded-xl object-cover"
                            />
                            <p class="font-semibold text-foreground text-sm">{{ prop.titulo }}</p>
                            <p class="mt-1 flex items-center justify-center gap-1 text-xs text-muted-foreground">
                                <MapPin class="h-3 w-3" />
                                {{ prop.localidad }}, {{ prop.departamento }}
                            </p>
                            <span
                                class="mt-2 inline-block rounded-full px-2 py-0.5 text-xs font-medium text-primary-foreground"
                                :class="prop.tipo_operacion === 'Venta' ? 'bg-primary' : 'bg-secondary'"
                            >
                                {{ prop.tipo_operacion }}
                            </span>
                            <div class="mt-3">
                                <Link
                                    :href="`/propiedades/${prop.id}`"
                                    class="text-xs text-primary hover:underline"
                                >
                                    Ver publicación →
                                </Link>
                            </div>
                        </th>
                    </tr>
                </thead>

                <!-- Filas de características -->
                <tbody>
                    <tr
                        v-for="campo in campos"
                        :key="campo.label"
                        class="even:bg-muted/30"
                    >
                        <td class="border-b border-r border-border p-3 text-sm font-medium text-muted-foreground">
                            <div class="flex items-center gap-2">
                                <component :is="campo.icon" class="h-4 w-4 text-primary shrink-0" />
                                {{ campo.label }}
                            </div>
                        </td>
                        <td
                            v-for="prop in propiedades"
                            :key="prop.id"
                            class="border-b border-r border-border p-3 text-center text-sm text-foreground last:border-r-0"
                        >
                            {{ campo.valor(prop) }}
                        </td>
                    </tr>

                    <!-- Fila amenidades -->
                    <tr>
                        <td class="border-r border-border p-3 text-sm font-medium text-muted-foreground align-top">
                            Amenidades
                        </td>
                        <td
                            v-for="prop in propiedades"
                            :key="prop.id"
                            class="border-r border-border p-3 text-center last:border-r-0"
                        >
                            <div v-if="prop.amenidades.length > 0" class="flex flex-wrap justify-center gap-1">
                                <span
                                    v-for="am in prop.amenidades"
                                    :key="am"
                                    class="rounded-full bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary"
                                >
                                    {{ am }}
                                </span>
                            </div>
                            <span v-else class="text-xs text-muted-foreground">Sin amenidades</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>