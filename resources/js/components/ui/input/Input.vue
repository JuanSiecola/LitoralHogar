<script setup lang="ts">
import type { HTMLAttributes, InputHTMLAttributes } from 'vue';
import { useVModel } from '@vueuse/core';
import { cn } from '@/lib/utils';

interface Props {
  id?: string;
  name?: string;
  type?: InputHTMLAttributes['type'];
  defaultValue?: string | number;
  modelValue?: string | number;
  placeholder?: string;
  autocomplete?: string;
  required?: boolean;
  readonly?: boolean;
  disabled?: boolean;
  min?: number | string;
  max?: number | string;
  step?: number | string;
  label?: string;
  error?: string;
  class?: HTMLAttributes['class'];
  inputClass?: HTMLAttributes['class'];
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  required: false,
  readonly: false,
  disabled: false,
});

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number | undefined): void;
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
});

function handleInput(event: Event) {
  const target = event.target as HTMLInputElement;
  if (props.type === 'number') {
    modelValue.value = target.value === '' ? undefined as any : Number(target.value);
  } else {
    modelValue.value = target.value;
  }
}
</script>

<template>
  <div :class="cn('grid gap-1.5', props.class)">
    <label
      v-if="props.label"
      :for="props.id"
      :class="cn(
        'text-base font-medium leading-none',
        props.disabled ? 'cursor-not-allowed text-neutral-400' : 'text-neutral-700 dark:text-neutral-200',
        props.error && 'text-danger',
      )"
    >
      {{ props.label }}
      <span v-if="props.required" class="ml-0.5 text-danger" aria-hidden="true">*</span>
    </label>

    <input
      :value="modelValue"
      @input="handleInput"
      :id="props.id"
      :name="props.name"
      :type="props.type"
      :placeholder="props.placeholder"
      :autocomplete="props.autocomplete"
      :required="props.required"
      :readonly="props.readonly"
      :disabled="props.disabled"
      :min="props.min"
      :max="props.max"
      :step="props.step"
      :aria-invalid="!!props.error"
      :aria-describedby="props.error ? `${props.id}-error` : undefined"
      data-slot="input"
      :class="cn(
        'file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input h-11 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
        'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
        props.error ? 'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive' : '',
        props.inputClass,
      )"
    />

    <p
      v-if="props.error"
      :id="`${props.id}-error`"
      class="text-xs font-medium text-danger"
      role="alert"
    >
      {{ props.error }}
    </p>
  </div>
</template>
