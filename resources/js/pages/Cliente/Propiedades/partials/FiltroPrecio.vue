<script setup lang="ts">
import {
    SliderRange,
    SliderRoot,
    SliderThumb,
    SliderTrack,
} from 'reka-ui';

defineProps<{
    moneda: string;
    precioMinFormateado: string;
    precioMaxFormateado: string;
    min: number;
    max: number;
    step: number;
}>();

const rango = defineModel<[number, number]>('rango', { required: true });
</script>

<template>
    <div class="border-t border-border pt-4">
        <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-muted-foreground">
            Rango de precio
        </p>

        <div class="rounded-lg border border-border bg-background p-4">
            <div class="mb-4 flex items-center justify-between gap-2">
                <span class="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary">
                    {{ precioMinFormateado }}
                </span>
                <span class="text-xs text-muted-foreground">a</span>
                <span class="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary">
                    {{ precioMaxFormateado }}
                </span>
            </div>

            <SliderRoot
                v-model="rango"
                :min="min"
                :max="max"
                :step="step"
                :min-steps-between-thumbs="1"
                class="relative flex h-5 w-full touch-none select-none items-center"
            >
                <SliderTrack class="relative h-1.5 grow rounded-full bg-muted">
                    <SliderRange class="absolute h-full rounded-full bg-primary" />
                </SliderTrack>
                <SliderThumb
                    class="block h-4 w-4 rounded-full border-2 border-primary bg-background shadow-sm transition-shadow hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary/40"
                    aria-label="Precio mínimo"
                />
                <SliderThumb
                    class="block h-4 w-4 rounded-full border-2 border-primary bg-background shadow-sm transition-shadow hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary/40"
                    aria-label="Precio máximo"
                />
            </SliderRoot>

            <div class="mt-2 flex justify-between text-[10px] text-muted-foreground">
                <span>{{ moneda }} 0</span>
                <span>{{ moneda }} 1.000.000+</span>
            </div>
        </div>
    </div>
</template>
