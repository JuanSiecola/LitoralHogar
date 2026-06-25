<script setup lang="ts">
const nroHabitaciones = defineModel<string>('nroHabitaciones', { required: true });
const nroBanios = defineModel<string>('nroBanios', { required: true });
const superficieMin = defineModel<string>('superficieMin', { required: true });
</script>

<template>
    <div class="space-y-3 border-t border-border pt-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">
            Características
        </p>

        <div>
            <label class="mb-1 block text-xs font-medium text-foreground">Habitaciones</label>
            <select
                v-model="nroHabitaciones"
                class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
            >
                <option value="">Cualquier cantidad</option>
                <option value="1">1+</option>
                <option value="2">2+</option>
                <option value="3">3+</option>
                <option value="4">4+</option>
            </select>
        </div>

        <div>
            <label class="mb-1 block text-xs font-medium text-foreground">Baños</label>
            <select
                v-model="nroBanios"
                class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
            >
                <option value="">Cualquier cantidad</option>
                <option value="1">1+</option>
                <option value="2">2+</option>
                <option value="3">3+</option>
                <option value="4">4+</option>
            </select>
        </div>

        <div>
            <label class="mb-1 block text-xs font-medium text-foreground">
                Superficie mínima (m²)
            </label>
            <input
                v-model="superficieMin"
                type="number"
                min="0"
                placeholder="Ej. 50"
                class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
            />
        </div>
    </div>
</template>
