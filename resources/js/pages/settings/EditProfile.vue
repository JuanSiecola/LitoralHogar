<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Building2, Camera, Phone, User, FileText, MapPin, Hash } from 'lucide-vue-next';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import FormField from '@/components/FormField.vue';
import FileUploadInput from '@/components/FileUploadInput.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';

interface DetalleInmobiliaria {
    razon_social: string;
    rut: string;
    direccion: string;
    logo_url: string | null;
}

interface Rol {
    id: number;
    nombre: string;
}

interface User {
    id: number;
    nombre: string;
    apellido: string;
    cedula: string;
    telefono: string;
    email: string;
    email_verified_at: string | null;
    avatar?: string;
    descripcion?: string;
    detalle_inmobiliaria?: DetalleInmobiliaria;
    rol_usuario?: Rol[];
}

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Editar perfil',
                href: edit(),
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user as User);

const isInmobiliaria = computed(() =>
    user.value.rol_usuario?.some(
        (r) => r.nombre.toLowerCase().includes('inmobiliaria'),
    ) ?? false,
);

const avatarPreview = ref<string>(user.value.avatar ?? '');
const logoPreview = ref<string>(user.value.detalle_inmobiliaria?.logo_url ?? '');

const handleAvatarChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) avatarPreview.value = URL.createObjectURL(file);
};

const handleLogoChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) logoPreview.value = URL.createObjectURL(file);
};

const userInitials = computed(() => {
    const n = user.value.nombre?.[0] ?? '';
    const a = user.value.apellido?.[0] ?? '';
    return `${n}${a}`.toUpperCase();
});
</script>

<template>
    <Head title="Editar perfil" />

    <h1 class="sr-only">Editar perfil</h1>

    <div class="flex flex-col space-y-8">

        <Heading
            variant="small"
            title="Editar perfil"
            :description="isInmobiliaria
                ? 'Actualizá los datos de tu inmobiliaria y tu información personal'
                : 'Actualizá tu información personal'"
        />

        <div class="flex items-center gap-5">
            <div class="relative">
                <!-- Avatar -->
                <div
                    class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-full border-2 border-primary/20 bg-primary/10 text-primary shadow-sm"
                >
                    <img
                        v-if="avatarPreview"
                        :src="avatarPreview"
                        alt="Foto de perfil"
                        class="h-full w-full object-cover"
                    />
                    <span v-else class="font-heading text-2xl font-semibold">
                        {{ userInitials }}
                    </span>
                </div>

                <!-- Botón cambiar foto -->
                <label
                    for="avatar"
                    class="absolute -bottom-1 -right-1 flex h-7 w-7 cursor-pointer items-center justify-center rounded-full border-2 border-white bg-primary text-white shadow-sm transition hover:bg-primary-600 dark:border-neutral-800"
                    title="Cambiar foto"
                >
                    <Camera class="h-3.5 w-3.5" />
                    <input
                        id="avatar"
                        type="file"
                        name="avatar"
                        accept="image/*"
                        class="sr-only"
                        @change="handleAvatarChange"
                    />
                </label>
            </div>

            <div>
                <p class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">
                    {{ user.nombre }} {{ user.apellido }}
                </p>
                <p class="text-xs text-neutral-500">
                    {{ isInmobiliaria ? 'Inmobiliaria' : 'Usuario' }} · {{ user.email }}
                </p>
                <p class="mt-1 text-xs text-neutral-400">
                    JPG, PNG o WEBP · máximo 2 MB
                </p>
            </div>
        </div>

        <Form
            v-bind="ProfileController.update.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <!-- Sección: datos personales -->
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-card dark:border-neutral-700 dark:bg-neutral-800/50">
                <div class="mb-5 flex items-center gap-2 border-b border-neutral-100 pb-4 dark:border-neutral-700">
                    <User class="h-4 w-4 text-primary" />
                    <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">
                        Datos personales
                    </h2>
                </div>

                <div class="grid gap-form-gap">
                    <!-- Nombre / Apellido -->
                    <div class="grid grid-cols-1 gap-form-gap md:grid-cols-2">
                        <FormField
                            id="nombre"
                            label="Nombre"
                            name="nombre"
                            :model-value="user.nombre"
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
                            :model-value="user.apellido"
                            :error="errors.apellido"
                            placeholder="Pérez"
                            autocomplete="family-name"
                            required
                            :tabindex="2"
                        />
                    </div>

                    <!-- Cédula / Teléfono -->
                    <div class="grid grid-cols-1 gap-form-gap md:grid-cols-2">
                        <FormField
                            id="cedula"
                            label="Cédula"
                            name="cedula"
                            :model-value="user.cedula"
                            :error="errors.cedula"
                            placeholder="12345678"
                            autocomplete="off"
                            required
                            :tabindex="3"
                        />
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
                                    :value="user.telefono"
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

                    <!-- Email -->
                    <FormField
                        id="email"
                        type="email"
                        label="Correo electrónico"
                        name="email"
                        :model-value="user.email"
                        :error="errors.email"
                        placeholder="juan@ejemplo.com"
                        autocomplete="email"
                        required
                        :tabindex="5"
                    />

                    <!-- Verificación de email -->
                    <div v-if="mustVerifyEmail && !user.email_verified_at" class="rounded-lg border border-warning/30 bg-warning/5 p-3">
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">
                            Tu correo no está verificado.
                            <Link   
                                :href="send()" 
                                as="button" 
                                class="font-medium text-primary underline underline-offset-2 hover:text-primary-600"> 
                                Reenviar email de verificación
                            </Link>
                        </p>
                        <p
                            v-if="status === 'verification-link-sent'"
                            class="mt-1 text-xs font-medium text-success"
                        >
                            ✓ Enlace enviado a tu correo.
                        </p>
                    </div>

                    <!-- Descripción -->
                    <div class="grid gap-1.5">
                        <label for="descripcion" class="flex items-center gap-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-200">
                            <FileText class="h-3.5 w-3.5 text-neutral-400" />
                            Descripción
                            <span class="ml-auto text-xs font-normal text-neutral-400">Opcional</span>
                        </label>
                        <textarea
                            id="descripcion"
                            name="descripcion"
                            :value="user.descripcion ?? ''"
                            rows="3"
                            placeholder="Contá algo sobre vos…"
                            :tabindex="6"
                            class="w-full rounded-md border border-neutral-300 bg-transparent px-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow,border-color] duration-150 placeholder:text-neutral-400 focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/20 dark:border-neutral-600 dark:bg-neutral-800/30"
                        />
                        <InputError :message="errors.descripcion" />
                    </div>
                </div>
            </div>

            <div
                v-if="isInmobiliaria"
                class="rounded-xl border border-secondary/30 bg-secondary/5 p-6 shadow-card dark:border-secondary/20 dark:bg-secondary/10"
            >
                <div class="mb-5 flex items-center gap-2 border-b border-secondary/20 pb-4">
                    <Building2 class="h-4 w-4 text-secondary-500" />
                    <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">
                        Datos de la inmobiliaria
                    </h2>
                </div>

                <div class="grid gap-form-gap">
                    <!-- Razón social / RUT -->
                    <div class="grid grid-cols-1 gap-form-gap md:grid-cols-2">
                        <FormField
                            id="razon_social"
                            label="Razón Social"
                            name="razon_social"
                            :default-value="user.detalle_inmobiliaria?.razon_social"
                            :error="errors.razon_social"
                            placeholder="Nombre de la inmobiliaria"
                            required
                            :tabindex="7"
                        />
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
                                    :value="user.detalle_inmobiliaria?.rut"
                                    placeholder="12345678-9"
                                    required
                                    :tabindex="8"
                                    class="h-11 w-full rounded-md border border-neutral-300 bg-transparent pl-9 pr-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow,border-color] duration-150 placeholder:text-neutral-400 focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/20 dark:border-neutral-600"
                                />
                            </div>
                            <InputError :message="errors.rut" />
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="grid gap-1.5">
                        <label for="direccion" class="flex items-center gap-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-200">
                            <MapPin class="h-3.5 w-3.5 text-neutral-400" />
                            Dirección
                        </label>
                        <input
                            id="direccion"
                            type="text"
                            name="direccion"
                            :value="user.detalle_inmobiliaria?.direccion"
                            placeholder="Calle Principal 123"
                            required
                            :tabindex="9"
                            class="h-11 w-full rounded-md border border-neutral-300 bg-transparent px-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow,border-color] duration-150 placeholder:text-neutral-400 focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/20 dark:border-neutral-600"
                        />
                        <InputError :message="errors.direccion" />
                    </div>

                    <!-- Logo -->
                    <div class="grid gap-2">
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
                            Logo de la inmobiliaria
                        </label>
                        <div class="flex items-center gap-4">
                            <!-- Preview logo -->
                            <div
                                v-if="logoPreview"
                                class="h-16 w-16 overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-xs"
                            >
                                <img :src="logoPreview" alt="Logo preview" class="h-full w-full object-contain p-1" />
                            </div>

                            <div class="flex-1">
                                <FileUploadInput
                                    label=""
                                    name="logo"
                                    accept="image/*"
                                    :tabindex="10"
                                    :errors="errors.logo"
                                    @change="handleLogoChange"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <Button
                    type="submit"
                    size="lg"
                    :disabled="processing"
                    :tabindex="11"
                    data-test="update-profile-button"
                    class="bg-primary hover:bg-primary-600"
                >
                    <Spinner v-if="processing" />
                    Guardar cambios
                </Button>
                <span
                    v-if="status === 'profile-updated'"
                    class="text-sm font-medium text-success"
                >
                    ✓ Cambios guardados
                </span>
            </div>
        </Form>
    </div>
</template>