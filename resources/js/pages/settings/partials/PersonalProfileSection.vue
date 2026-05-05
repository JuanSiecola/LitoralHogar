<script setup lang="ts">
import { CreditCard, Phone, User } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import FormField from '@/components/FormField.vue';
import type { PerfilPersona } from '@/types/user';

const props = defineProps<{
    perfil?: PerfilPersona;
    rolNombre: string;
    errors: Record<string, string>;
}>();
</script>

<template>
    <div
        class="rounded-xl border border-neutral-200 bg-white p-6 shadow-card dark:border-neutral-700 dark:bg-neutral-800/50"
    >
        <div
            class="mb-6 flex items-center justify-between border-b border-neutral-100 pb-4 dark:border-neutral-700"
        >
            <div class="flex items-center gap-2">
                <User class="h-4 w-4 text-primary" />
                <h2
                    class="text-sm font-semibold text-neutral-700 dark:text-neutral-200"
                >
                    Datos personales
                </h2>
            </div>
            <span
                class="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary capitalize"
            >
                {{ rolNombre }}
            </span>
        </div>

        <div class="grid gap-form-gap">
            <!-- Nombre / Apellido -->
            <div class="grid grid-cols-1 gap-form-gap md:grid-cols-2">
                <FormField
                    id="nombre"
                    label="Nombre"
                    name="nombre"
                    :default-value="perfil?.nombre"
                    :error="errors.nombre"
                    placeholder="Juan"
                    autocomplete="given-name"
                    required
                    :tabindex="1"
                />
                <FormField
                    id="apellido"
                    label="Apellido"
                    name="apellido"
                    :default-value="perfil?.apellido"
                    :error="errors.apellido"
                    placeholder="Pérez"
                    autocomplete="family-name"
                    required
                    :tabindex="2"
                />
            </div>

            <!-- Cédula / Teléfono -->
            <div class="grid grid-cols-1 gap-form-gap md:grid-cols-2">
                <!-- Cédula con ícono -->
                <div class="grid gap-1.5">
                    <label
                        for="cedula"
                        class="text-sm font-medium text-neutral-700 dark:text-neutral-200"
                    >
                        Cédula
                    </label>
                    <div class="relative">
                        <CreditCard
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-neutral-400"
                        />
                        <input
                            id="cedula"
                            type="text"
                            name="cedula"
                            :value="perfil?.cedula ?? undefined"
                            placeholder="12345678"
                            autocomplete="off"
                            :tabindex="3"
                            :class="[
                                'h-11 w-full rounded-md border bg-transparent py-2 pr-3 pl-9 text-sm shadow-xs transition-[color,box-shadow,border-color] duration-150 outline-none',
                                'placeholder:text-neutral-400',
                                errors.cedula
                                    ? 'border-danger focus-visible:border-danger focus-visible:ring-danger/20 focus-visible:ring-2'
                                    : 'border-neutral-300 focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/20 dark:border-neutral-600',
                            ]"
                        />
                    </div>
                    <InputError :message="errors.cedula" />
                </div>

                <!-- Teléfono con ícono -->
                <div class="grid gap-1.5">
                    <label
                        for="telefono"
                        class="text-sm font-medium text-neutral-700 dark:text-neutral-200"
                    >
                        Teléfono <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <Phone
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-neutral-400"
                        />
                        <input
                            id="telefono"
                            type="tel"
                            name="telefono"
                            :value="perfil?.telefono"
                            placeholder="092247856"
                            autocomplete="tel"
                            required
                            :tabindex="4"
                            :class="[
                                'h-11 w-full rounded-md border bg-transparent py-2 pr-3 pl-9 text-sm shadow-xs transition-[color,box-shadow,border-color] duration-150 outline-none',
                                'placeholder:text-neutral-400',
                                errors.telefono
                                    ? 'border-danger focus-visible:border-danger focus-visible:ring-danger/20 focus-visible:ring-2'
                                    : 'border-neutral-300 focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/20 dark:border-neutral-600',
                            ]"
                        />
                    </div>
                    <InputError :message="errors.telefono" />
                </div>
            </div>
        </div>
    </div>
</template>
