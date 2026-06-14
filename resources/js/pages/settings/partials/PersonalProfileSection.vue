<script setup lang="ts">
import { User } from 'lucide-vue-next';
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
    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-neutral-100 bg-neutral-50/60 px-6 py-4 dark:border-neutral-700 dark:bg-neutral-800/40">
            <div class="flex items-center gap-2.5">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10">
                    <User class="h-3.5 w-3.5 text-primary" />
                </div>
                <h2 class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">Datos personales</h2>
            </div>
            <span class="rounded-full border border-primary/20 bg-primary/8 px-3 py-1 text-xs font-medium capitalize text-primary">
                {{ rolNombre }}
            </span>
        </div>

        <div class="p-6">
            <div class="grid gap-5">
                <!-- Nombre / Apellido -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="grid gap-1.5">
                        <label for="nombre" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                            Nombre <span class="text-danger normal-case tracking-normal">*</span>
                        </label>
                        <input
                            id="nombre"
                            type="text"
                            name="nombre"
                            :value="perfil?.nombre"
                            placeholder="Juan"
                            autocomplete="given-name"
                            required
                            :tabindex="1"
                            :class="[
                                'h-10 w-full rounded-lg border bg-white px-3 py-2 text-sm text-neutral-900 transition-all duration-150 outline-none placeholder:text-neutral-300 dark:bg-neutral-800 dark:text-neutral-100 dark:placeholder:text-neutral-600',
                                errors.nombre
                                    ? 'border-danger/60 ring-2 ring-danger/15'
                                    : 'border-neutral-200 hover:border-neutral-300 focus:border-primary focus:ring-2 focus:ring-primary/15 dark:border-neutral-700 dark:hover:border-neutral-600',
                            ]"
                        />
                        <InputError :message="errors.nombre" />
                    </div>

                    <div class="grid gap-1.5">
                        <label for="apellido" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                            Apellido <span class="text-danger normal-case tracking-normal">*</span>
                        </label>
                        <input
                            id="apellido"
                            type="text"
                            name="apellido"
                            :value="perfil?.apellido"
                            placeholder="Pérez"
                            autocomplete="family-name"
                            required
                            :tabindex="2"
                            :class="[
                                'h-10 w-full rounded-lg border bg-white px-3 py-2 text-sm text-neutral-900 transition-all duration-150 outline-none placeholder:text-neutral-300 dark:bg-neutral-800 dark:text-neutral-100 dark:placeholder:text-neutral-600',
                                errors.apellido
                                    ? 'border-danger/60 ring-2 ring-danger/15'
                                    : 'border-neutral-200 hover:border-neutral-300 focus:border-primary focus:ring-2 focus:ring-primary/15 dark:border-neutral-700 dark:hover:border-neutral-600',
                            ]"
                        />
                        <InputError :message="errors.apellido" />
                    </div>
                </div>

                <!-- Cédula / Teléfono -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="grid gap-1.5">
                        <label for="cedula" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                            Cédula
                        </label>
                        <input
                            id="cedula"
                            type="text"
                            name="cedula"
                            :value="perfil?.cedula ?? undefined"
                            placeholder="12345678"
                            autocomplete="off"
                            :tabindex="3"
                            :class="[
                                'h-10 w-full rounded-lg border bg-white px-3 py-2 text-sm text-neutral-900 transition-all duration-150 outline-none placeholder:text-neutral-300 dark:bg-neutral-800 dark:text-neutral-100 dark:placeholder:text-neutral-600',
                                errors.cedula
                                    ? 'border-danger/60 ring-2 ring-danger/15'
                                    : 'border-neutral-200 hover:border-neutral-300 focus:border-primary focus:ring-2 focus:ring-primary/15 dark:border-neutral-700 dark:hover:border-neutral-600',
                            ]"
                        />
                        <InputError :message="errors.cedula" />
                    </div>

                    <div class="grid gap-1.5">
                        <label for="telefono" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                            Teléfono <span class="text-danger normal-case tracking-normal">*</span>
                        </label>
                        <input
                            id="telefono"
                            type="tel"
                            name="telefono"
                            :value="perfil?.telefono"
                            placeholder="092 247 856"
                            autocomplete="tel"
                            required
                            :tabindex="4"
                            :class="[
                                'h-10 w-full rounded-lg border bg-white px-3 py-2 text-sm text-neutral-900 transition-all duration-150 outline-none placeholder:text-neutral-300 dark:bg-neutral-800 dark:text-neutral-100 dark:placeholder:text-neutral-600',
                                errors.telefono
                                    ? 'border-danger/60 ring-2 ring-danger/15'
                                    : 'border-neutral-200 hover:border-neutral-300 focus:border-primary focus:ring-2 focus:ring-primary/15 dark:border-neutral-700 dark:hover:border-neutral-600',
                            ]"
                        />
                        <InputError :message="errors.telefono" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>