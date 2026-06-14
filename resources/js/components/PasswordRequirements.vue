<script setup lang="ts">
import { computed } from 'vue';
import { Check, X } from 'lucide-vue-next';

const props = defineProps<{
    password: string;
}>();

const requisitos = computed(() => [
    {
        texto: 'Al menos 8 caracteres',
        cumple: props.password.length >= 8,
    },
    {
        texto: 'Una letra mayúscula y una minúscula',
        cumple: /[a-z]/.test(props.password) && /[A-Z]/.test(props.password),
    },
    {
        texto: 'Al menos un símbolo (!@#$%^&*...)',
        cumple: /[^a-zA-Z0-9]/.test(props.password),
    },
]);
</script>

<template>
    <div class="rounded-md border border-border bg-muted/40 p-3">
        <p class="mb-2 text-xs font-medium text-muted-foreground">
            La contraseña debe contener:
        </p>
        <ul class="grid gap-1.5">
            <li
                v-for="req in requisitos"
                :key="req.texto"
                class="flex items-center gap-2 text-xs"
                :class="req.cumple ? 'text-green-600 dark:text-green-500' : 'text-muted-foreground'"
            >
                <Check v-if="req.cumple" class="size-3.5 shrink-0" />
                <X v-else class="size-3.5 shrink-0" />
                <span>{{ req.texto }}</span>
            </li>
        </ul>
    </div>
</template>
