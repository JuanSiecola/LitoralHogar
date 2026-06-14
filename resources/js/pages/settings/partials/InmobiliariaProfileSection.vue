<script setup lang="ts">
import { Building2 } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import { InmobiliariaData } from '@/types/user';

const props = defineProps<{
    inmobiliaria?: InmobiliariaData;
    errors: Record<string, string>;
}>();
</script>

<template>
    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-neutral-100 bg-neutral-50/60 px-6 py-4 dark:border-neutral-700 dark:bg-neutral-800/40">
            <div class="flex items-center gap-2.5">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10">
                    <Building2 class="h-3.5 w-3.5 text-primary" />
                </div>
                <h2 class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">Datos de la inmobiliaria</h2>
            </div>
            <span class="rounded-full border border-primary/20 bg-primary/8 px-3 py-1 text-xs font-medium text-primary">
                Inmobiliaria
            </span>
        </div>

        <div class="p-6">
            <div class="grid gap-5">
                <!-- Razón social / RUT -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="grid gap-1.5">
                        <label for="razon_social" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                            Razón Social <span class="text-danger normal-case tracking-normal">*</span>
                        </label>
                        <input
                            id="razon_social"
                            type="text"
                            name="razon_social"
                            :value="inmobiliaria?.razon_social"
                            placeholder="Nombre de la inmobiliaria"
                            required
                            :tabindex="1"
                            :class="[
                                'h-10 w-full rounded-lg border bg-white px-3 py-2 text-sm text-neutral-900 transition-all duration-150 outline-none placeholder:text-neutral-300 dark:bg-neutral-800 dark:text-neutral-100 dark:placeholder:text-neutral-600',
                                errors.razon_social
                                    ? 'border-danger/60 ring-2 ring-danger/15'
                                    : 'border-neutral-200 hover:border-neutral-300 focus:border-primary focus:ring-2 focus:ring-primary/15 dark:border-neutral-700 dark:hover:border-neutral-600',
                            ]"
                        />
                        <InputError :message="errors.razon_social" />
                    </div>

                    <div class="grid gap-1.5">
                        <label for="rut" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                            RUT <span class="text-danger normal-case tracking-normal">*</span>
                        </label>
                        <input
                            id="rut"
                            type="text"
                            name="rut"
                            :value="inmobiliaria?.rut"
                            placeholder="12345678"
                            required
                            :tabindex="2"
                            :class="[
                                'h-10 w-full rounded-lg border bg-white px-3 py-2 text-sm text-neutral-900 transition-all duration-150 outline-none placeholder:text-neutral-300 dark:bg-neutral-800 dark:text-neutral-100 dark:placeholder:text-neutral-600',
                                errors.rut
                                    ? 'border-danger/60 ring-2 ring-danger/15'
                                    : 'border-neutral-200 hover:border-neutral-300 focus:border-primary focus:ring-2 focus:ring-primary/15 dark:border-neutral-700 dark:hover:border-neutral-600',
                            ]"
                        />
                        <InputError :message="errors.rut" />
                    </div>
                </div>

                <!-- Dirección (ancho completo) -->
                <div class="grid gap-1.5">
                    <label for="direccion" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                        Dirección <span class="text-danger normal-case tracking-normal">*</span>
                    </label>
                    <input
                        id="direccion"
                        type="text"
                        name="direccion"
                        :value="inmobiliaria?.direccion"
                        placeholder="Calle Principal 123, Montevideo"
                        required
                        :tabindex="3"
                        :class="[
                            'h-10 w-full rounded-lg border bg-white px-3 py-2 text-sm text-neutral-900 transition-all duration-150 outline-none placeholder:text-neutral-300 dark:bg-neutral-800 dark:text-neutral-100 dark:placeholder:text-neutral-600',
                            errors.direccion
                                ? 'border-danger/60 ring-2 ring-danger/15'
                                : 'border-neutral-200 hover:border-neutral-300 focus:border-primary focus:ring-2 focus:ring-primary/15 dark:border-neutral-700 dark:hover:border-neutral-600',
                        ]"
                    />
                    <InputError :message="errors.direccion" />
                </div>

                <!-- Teléfono -->
                <div class="grid gap-1.5 sm:w-1/2">
                    <label for="telefono" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                        Teléfono <span class="text-danger normal-case tracking-normal">*</span>
                    </label>
                    <input
                        id="telefono"
                        type="tel"
                        name="telefono"
                        :value="inmobiliaria?.telefono"
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
</template>