<script setup lang="ts">
import { Form, Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Building2, Camera, CheckCircle2 } from 'lucide-vue-next';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import ProfilePhotoController from '@/actions/App/Http/Controllers/Settings/ProfilePhotoController';
import DeleteUser from '@/components/DeleteUser.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { send } from '@/routes/verification';
import InmobiliariaProfileSection from '../settings/partials/InmobiliariaProfileSection.vue';
import PanelLayout from '@/layouts/PanelLayout.vue';
import { useInmobiliariaNav } from '@/composables/useInmobiliariaNav';
import type { User as UserType } from '@/types/user';

type Props = { mustVerifyEmail: boolean; status?: string };
defineProps<Props>();

const navLinks = useInmobiliariaNav();
const page = usePage();
const user = computed(() => page.props.auth.user as UserType);

const displayName = computed(() => user.value.inmobiliaria?.razon_social ?? 'Inmobiliaria');

const avatarInitials = computed(() => {
    const name = user.value.inmobiliaria?.razon_social ?? '';
    return name.split(' ').slice(0, 2).map((w) => w[0]).join('').toUpperCase() || 'IN';
});

const avatarBase = computed(() => user.value.inmobiliaria?.logo_url ?? '');
const avatarPreview = ref<string>(avatarBase.value);
const photoForm = useForm({ photo: null as File | null });

const handleAvatarChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (!file) return;
    avatarPreview.value = URL.createObjectURL(file);
    photoForm.photo = file;
    photoForm.post(ProfilePhotoController.store.url(), { forceFormData: true });
};
</script>

<template>
    <Head title="Mi Perfil" />
    <PanelLayout :navLinks="navLinks">
        <div class="mx-auto max-w-2xl space-y-6">

            <!-- Encabezado de página -->
            <div>
                <h1 class="text-xl font-semibold text-neutral-900 dark:text-white">Mi Perfil</h1>
                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Administrá los datos de tu inmobiliaria</p>
            </div>

            <!-- Tarjeta de logo -->
            <div class="flex items-center gap-5 rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <!-- Logo con botón de cambio -->
                <div class="relative shrink-0">
                    <div class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-2xl bg-primary/10 text-primary ring-2 ring-primary/15">
                        <img v-if="avatarPreview" :src="avatarPreview" alt="Logo de la inmobiliaria" class="h-full w-full object-cover" />
                        <span v-else class="text-2xl font-bold tracking-tight">{{ avatarInitials }}</span>
                    </div>
                    <label
                        for="avatar-input"
                        :class="[
                            'absolute -right-1.5 -bottom-1.5 flex h-7 w-7 items-center justify-center rounded-full border-2 border-white bg-primary shadow-sm transition-all dark:border-neutral-900',
                            photoForm.processing
                                ? 'pointer-events-none cursor-wait opacity-60'
                                : 'cursor-pointer hover:bg-primary/90 hover:scale-105',
                        ]"
                        title="Cambiar logo"
                    >
                        <Camera v-if="!photoForm.processing" class="h-3.5 w-3.5 text-white" />
                        <Spinner v-else class="h-3.5 w-3.5 text-white" />
                        <input id="avatar-input" type="file" accept="image/jpg,image/jpeg,image/png,image/webp" class="sr-only" @change="handleAvatarChange" />
                    </label>
                </div>

                <!-- Info de la inmobiliaria -->
                <div class="min-w-0 flex-1">
                    <p class="truncate font-semibold text-neutral-900 dark:text-white">{{ displayName }}</p>
                    <div class="mt-0.5 flex items-center gap-1.5">
                        <Building2 class="h-3.5 w-3.5 shrink-0 text-neutral-400" />
                        <p class="truncate text-sm text-neutral-500 dark:text-neutral-400">Inmobiliaria · {{ user.email }}</p>
                    </div>
                    <p class="mt-2 text-xs text-neutral-400 dark:text-neutral-600">JPG, PNG o WEBP · máx. 2 MB</p>
                </div>
            </div>

            <!-- Formulario -->
            <Form :action="ProfileController.update.url()" method="patch" class="space-y-4" v-slot="{ errors, processing }">

                <!-- Email -->
                <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <div class="flex items-center gap-2.5 border-b border-neutral-100 bg-neutral-50/60 px-6 py-4 dark:border-neutral-700 dark:bg-neutral-800/40">
                        <h2 class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">Correo electrónico</h2>
                    </div>
                    <div class="p-6 grid gap-1.5">
                        <label for="email" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                            Correo electrónico <span class="text-danger normal-case tracking-normal">*</span>
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            :value="user.email"
                            placeholder="contacto@inmobiliaria.com"
                            autocomplete="email"
                            required
                            :class="[
                                'h-10 w-full rounded-lg border bg-white px-3 py-2 text-sm text-neutral-900 transition-all duration-150 outline-none placeholder:text-neutral-300 dark:bg-neutral-800 dark:text-neutral-100',
                                errors.email
                                    ? 'border-danger/60 ring-2 ring-danger/15'
                                    : 'border-neutral-200 hover:border-neutral-300 focus:border-primary focus:ring-2 focus:ring-primary/15 dark:border-neutral-700',
                            ]"
                        />
                        <p v-if="errors.email" class="flex items-center gap-1 text-xs font-medium text-danger" role="alert">{{ errors.email }}</p>

                        <!-- Verificación pendiente -->
                        <div v-if="mustVerifyEmail && !user.email_verified_at" class="mt-2 rounded-xl border border-warning/25 bg-warning/5 p-3.5">
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                                Tu correo no está verificado.
                                <Link :href="send()" as="button" class="font-medium text-primary underline underline-offset-2 hover:text-primary/80">
                                    Reenviar verificación
                                </Link>
                            </p>
                            <p v-if="status === 'verification-link-sent'" class="mt-1.5 flex items-center gap-1 text-xs font-medium text-success">
                                <CheckCircle2 class="h-3.5 w-3.5" /> Enlace enviado a tu correo.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Datos de la inmobiliaria -->
                <InmobiliariaProfileSection :inmobiliaria="user.inmobiliaria" :errors="errors" />

                <!-- Acciones -->
                <div class="flex items-center gap-3 pt-1">
                    <Button type="submit" :disabled="processing" class="min-w-32">
                        <Spinner v-if="processing" class="mr-2 h-4 w-4" />
                        {{ processing ? 'Guardando…' : 'Guardar cambios' }}
                    </Button>
                    <span v-if="status === 'profile-updated'" class="flex items-center gap-1.5 text-sm font-medium text-success">
                        <CheckCircle2 class="h-4 w-4" /> Cambios guardados
                    </span>
                </div>
            </Form>

            <!-- Zona peligrosa -->
            <div class="border-t border-neutral-100 pt-4 dark:border-neutral-800">
                <DeleteUser />
            </div>
        </div>
    </PanelLayout>
</template>