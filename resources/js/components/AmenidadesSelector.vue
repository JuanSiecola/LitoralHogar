<script setup lang="ts">
import { computed } from 'vue'

interface Amenidad {
    id: number
    nombre: string
}

const props = defineProps<{
    amenidades: Amenidad[]
    modelValue: Array<number | string>
}>()

const emit = defineEmits<{
    (e: 'update:modelValue', value: number[]): void
}>()

const selectedAmenidadIds = computed(() => props.modelValue.map(Number))

function handleChange(id: number, event: Event) {
    const target = event.target as HTMLInputElement
    const currentIds = selectedAmenidadIds.value

    if (target.checked) {
        if (!currentIds.includes(id)) {
            emit('update:modelValue', [...currentIds, id])
        }
        return
    }

    emit('update:modelValue', currentIds.filter(selectedId => selectedId !== id))
}
</script>

<template>
    <div class="flex flex-wrap gap-3">
        <label
            v-for="amenidad in amenidades"
            :key="amenidad.id"
            class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-colors select-none"
            :class="selectedAmenidadIds.includes(amenidad.id)
                ? 'border-primary bg-primary/5 text-primary'
                : 'border-neutral-200 hover:border-neutral-400 text-neutral-700'"
        >
            <input
                type="checkbox"
                class="size-4 rounded border-neutral-300 text-primary focus:ring-primary"
                :checked="selectedAmenidadIds.includes(amenidad.id)"
                @change="handleChange(amenidad.id, $event)"
            />
            <span class="text-sm">{{ amenidad.nombre }}</span>
        </label>
    </div>
</template>