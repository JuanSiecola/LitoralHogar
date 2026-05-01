<script setup lang="ts">
import type { HTMLAttributes, InputHTMLAttributes } from 'vue';
import { useVModel } from '@vueuse/core';
import { cn } from '@/lib/utils';

interface Props {
    // Campo
    id: string;
    name?: string;
    type?: InputHTMLAttributes['type'];
    placeholder?: string;
    autocomplete?: string;
    required?: boolean;
    readonly?: boolean;

    // Valor
    modelValue?: string | number;
    defaultValue?: string | number;

    // Label
    label?: string;

    // Estado
    disabled?: boolean;
    error?: string;

    // Estilos
    class?: HTMLAttributes['class'];
    inputClass?: HTMLAttributes['class'];
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    disabled: false,
    required: false,
    readonly: false,
});

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void;
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
});
</script>

<template>
    <div :class="cn('grid gap-1.5', props.class)">
        <!-- Label -->
        <label
            v-if="label"
            :for="id"
            :class="cn(
                'text-sm font-medium leading-none',
                disabled
                    ? 'cursor-not-allowed text-neutral-400'
                    : 'text-neutral-700 dark:text-neutral-200',
                error && 'text-danger',
            )"
        >
            {{ label }}
            <span v-if="required" class="ml-0.5 text-danger" aria-hidden="true">*</span>
        </label>

        <!-- Input -->
        <input
            :id="id"
            v-model="modelValue"
            :name="name ?? id"
            :type="type"
            :placeholder="placeholder"
            :autocomplete="autocomplete"
            :required="required"
            :readonly="readonly"
            :disabled="disabled"
            :aria-invalid="!!error"
            :aria-describedby="error ? `${id}-error` : undefined"
            data-slot="input"
            :class="cn(
                // Base
                'h-11 w-full min-w-0 rounded-md border bg-transparent px-input-px py-input-py text-sm shadow-xs outline-none transition-[color,box-shadow,border-color] duration-150',
                'placeholder:text-neutral-400',
                // Borde normal
                'border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800/30',
                // Focus
                'focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/20',
                // Error
                error && 'border-danger focus-visible:border-danger focus-visible:ring-danger/20',
                // Disabled
                disabled && 'cursor-not-allowed border-neutral-200 bg-neutral-50 text-neutral-400 dark:border-neutral-700 dark:bg-neutral-900/50',
                // Readonly
                readonly && 'bg-neutral-50 dark:bg-neutral-900/50',
                inputClass,
            )"
        />

        <!-- Error -->
        <p
            v-if="error"
            :id="`${id}-error`"
            class="flex items-center gap-1 text-xs font-medium text-danger"
            role="alert"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-3.5 w-3.5 shrink-0"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true"
            >
                <path
                    fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd"
                />
            </svg>
            {{ error }}
        </p>
    </div>
</template>