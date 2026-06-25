<script setup lang="ts">
const tipoOperacion = defineModel<string>('tipoOperacion', { required: true });
const tipoPropiedad = defineModel<string>('tipoPropiedad', { required: true });
</script>

<template>
    <div class="space-y-3">
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                Operación
            </label>
            <select
                v-model="tipoOperacion"
                class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
            >
                <option value="">Todas</option>
                <option value="Venta">Venta</option>
                <option value="Alquiler">Alquiler</option>
            </select>
        </div>

        <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                Tipo de propiedad
            </label>
            <select
                v-model="tipoPropiedad"
                class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
            >
                <option value="">Todos</option>
                <option value="Casa">Casa</option>
                <option value="Apartamento">Apartamento</option>
                <option value="Local">Local</option>
                <option value="Terreno">Terreno</option>
                <option value="Oficina">Oficina</option>
            </select>
        </div>
    </div>
</template>
