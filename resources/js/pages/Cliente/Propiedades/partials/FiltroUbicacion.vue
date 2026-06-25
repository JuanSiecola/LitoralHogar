<script setup lang="ts">
import { Loader2 } from 'lucide-vue-next';
import { useLocalidades } from '@/composables/useLocalidades';
import type { OpcionUbicacion } from '@/types';

defineProps<{
    departamentos: OpcionUbicacion[];
}>();

const departamentoId = defineModel<string>('departamentoId', { required: true });
const localidadId = defineModel<string>('localidadId', { required: true });

const { localidades, cargandoLocalidades } = useLocalidades(departamentoId, {
    onChange: () => {
        localidadId.value = '';
    },
});
</script>

<template>
    <div class="space-y-3 border-t border-border pt-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">
            Ubicación
        </p>

        <div>
            <label class="mb-1 block text-xs font-medium text-foreground">
                Departamento
            </label>
            <select
                v-model="departamentoId"
                class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
            >
                <option value="">Todos los departamentos</option>
                <option
                    v-for="dep in departamentos"
                    :key="dep.id"
                    :value="dep.id.toString()"
                >
                    {{ dep.nombre }}
                </option>
            </select>
        </div>

        <div>
            <label class="mb-1 flex items-center gap-2 text-xs font-medium text-foreground">
                Localidad
                <Loader2
                    v-if="cargandoLocalidades"
                    class="h-3 w-3 animate-spin text-primary"
                />
            </label>
            <select
                v-model="localidadId"
                :disabled="!departamentoId || cargandoLocalidades"
                class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary disabled:cursor-not-allowed disabled:opacity-50"
            >
                <option value="">
                    {{ departamentoId ? 'Todas las localidades' : 'Seleccioná un departamento' }}
                </option>
                <option
                    v-for="loc in localidades"
                    :key="loc.id"
                    :value="loc.id.toString()"
                >
                    {{ loc.nombre }}
                </option>
            </select>
        </div>
    </div>
</template>
