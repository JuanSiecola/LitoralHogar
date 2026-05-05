<script setup lang="ts">
import { Building2, Hash, MapPin, Phone } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';

interface InmobiliariaData {
    razon_social: string;
    rut: string;
    direccion: string;
    telefono: string;
    logo_url: string | null;
}

const props = defineProps<{
    inmobiliaria?: InmobiliariaData;
    errors: Record<string, string>;
}>();
</script>

<template>
    <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-card dark:border-neutral-700 dark:bg-neutral-800/50">
        <div class="mb-6 flex items-center justify-between border-b border-neutral-100 pb-4 dark:border-neutral-700">
            <div class="flex items-center gap-2">
                <Building2 class="h-4 w-4 text-primary" />
                <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">
                    Datos de la inmobiliaria
                </h2>
            </div>
            <span class="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary">
                Inmobiliaria
            </span>
        </div>

        <div class="grid gap-form-gap">
            <!-- Razón social / RUT -->
            <div class="grid grid-cols-1 gap-form-gap md:grid-cols-2">
                <!-- Razón social -->
                <div class="grid gap-1.5">
                    <label for="razon_social" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
                        Razón Social <span class="text-danger">*</span>
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
                            'h-11 w-full rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow,border-color] duration-150',
                            'placeholder:text-neutral-400',
                            errors.razon_social
                                ? 'border-danger focus-visible:border-danger focus-visible:ring-2 focus-visible:ring-danger/20'
                                : 'border-neutral-300 focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/20 dark:border-neutral-600',
                        ]"
                    />
                    <InputError :message="errors.razon_social" />
                </div>

                <!-- RUT -->
                <div class="grid gap-1.5">
                    <label for="rut" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
                        RUT <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <Hash class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-neutral-400" />
                        <input
                            id="rut"
                            type="text"
                            name="rut"
                            :value="inmobiliaria?.rut"
                            placeholder="12345678"
                            required
                            :tabindex="2"
                            :class="[
                                'h-11 w-full rounded-md border bg-transparent pl-9 pr-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow,border-color] duration-150',
                                'placeholder:text-neutral-400',
                                errors.rut
                                    ? 'border-danger focus-visible:border-danger focus-visible:ring-2 focus-visible:ring-danger/20'
                                    : 'border-neutral-300 focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/20 dark:border-neutral-600',
                            ]"
                        />
                    </div>
                    <InputError :message="errors.rut" />
                </div>
            </div>

            <!-- Dirección / Teléfono -->
            <div class="grid grid-cols-1 gap-form-gap md:grid-cols-2">
                <!-- Dirección -->
                <div class="grid gap-1.5">
                    <label for="direccion" class="flex items-center gap-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-200">
                        <MapPin class="h-3.5 w-3.5 text-neutral-400" />
                        Dirección <span class="ml-0.5 text-danger">*</span>
                    </label>
                    <input
                        id="direccion"
                        type="text"
                        name="direccion"
                        :value="inmobiliaria?.direccion"
                        placeholder="Calle Principal 123"
                        required
                        :tabindex="3"
                        :class="[
                            'h-11 w-full rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow,border-color] duration-150',
                            'placeholder:text-neutral-400',
                            errors.direccion
                                ? 'border-danger focus-visible:border-danger focus-visible:ring-2 focus-visible:ring-danger/20'
                                : 'border-neutral-300 focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/20 dark:border-neutral-600',
                        ]"
                    />
                    <InputError :message="errors.direccion" />
                </div>

                <!-- Teléfono -->
                <div class="grid gap-1.5">
                    <label for="telefono" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
                        Teléfono <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <Phone class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-neutral-400" />
                        <input
                            id="telefono"
                            type="tel"
                            name="telefono"
                            :value="inmobiliaria?.telefono"
                            placeholder="092247856"
                            autocomplete="tel"
                            required
                            :tabindex="4"
                            :class="[
                                'h-11 w-full rounded-md border bg-transparent pl-9 pr-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow,border-color] duration-150',
                                'placeholder:text-neutral-400',
                                errors.telefono
                                    ? 'border-danger focus-visible:border-danger focus-visible:ring-2 focus-visible:ring-danger/20'
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
