<script setup lang="ts">
import { Form, Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Camera, User } from 'lucide-vue-next';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import ProfilePhotoController from '@/actions/App/Http/Controllers/Settings/ProfilePhotoController';
import DeleteUser from '@/components/DeleteUser.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { send } from '@/routes/verification';
import PersonalProfileSection from '../settings/partials/PersonalProfileSection.vue';
import PanelLayout from '@/layouts/PanelLayout.vue';
import { useClienteNav } from '@/composables/useClienteNav';
import type { User as UserType } from '@/types/user';

type Props = { mustVerifyEmail: boolean; status?: string };
defineProps<Props>();

const navLinks = useClienteNav();
const page = usePage();
const user = computed(() => page.props.auth.user as UserType);

const displayName = computed(() => {
    const p = user.value.perfil_persona;
    return p ? `${p.nombre} ${p.apellido}` : 'Cliente';
});

const avatarInitials = computed(() => {
    const p = user.value.perfil_persona;
    if (!p) return '';
    return [p.nombre, p.apellido].filter(Boolean).map((w) => w[0]).join('').toUpperCase();
});

const avatarBase = computed(() => user.value.perfil_persona?.foto_url ?? '');
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
        <div class="flex flex-col space-y-8 max-w-2xl">
            <h1 class="mb-1 text-2xl font-semibold text-foreground">Mi Perfil</h1>
            <p class="text-sm text-muted-foreground">Actualizá tus datos personales</p>

            <!-- Avatar -->
            <div class="flex items-center gap-5">
                <div class="relative shrink-0">
                    <div class="flex h-28 w-28 items-center justify-center overflow-hidden rounded-full border-2 border-primary/20 text-primary shadow-sm">
                        <img v-if="avatarPreview" :src="avatarPreview" alt="Foto" class="h-full w-full object-cover" />
                        <span v-else class="font-heading text-3xl font-semibold">{{ avatarInitials }}</span>
                    </div>
                    <label for="avatar-input" :class="[
                        'absolute right-0 bottom-0 flex h-8 w-8 items-center justify-center rounded-full border-2 bg-primary-foreground shadow-sm transition dark:border-neutral-800',
                        photoForm.processing ? 'pointer-events-none cursor-wait opacity-60' : 'cursor-pointer hover:bg-primary/80',
                    ]" title="Cambiar foto">
                        <Camera v-if="!photoForm.processing" class="h-4 w-4" />
                        <Spinner v-else class="h-4 w-4" />
                        <input id="avatar-input" type="file" accept="image/jpg,image/jpeg,image/png,image/webp" class="sr-only" @change="handleAvatarChange" />
                    </label>
                </div>
                <div>
                    <p class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">{{ displayName }}</p>
                    <div class="mt-0.5 flex items-center gap-1.5">
                        <User class="h-3.5 w-3.5 text-neutral-400" />
                        <p class="text-xs text-neutral-500">Cliente · {{ user.email }}</p>
                    </div>
                    <p class="mt-1 text-xs text-neutral-400">JPG, PNG o WEBP · máx. 2 MB</p>
                </div>
            </div>

            <Form :action="ProfileController.update.url()" method="patch" class="space-y-6" v-slot="{ errors, processing }">
                <!-- Email -->
                <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-card dark:border-neutral-700 dark:bg-neutral-800/50">
                    <div class="mb-5 border-b border-neutral-100 pb-4 dark:border-neutral-700">
                        <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">Correo electrónico</h2>
                    </div>
                    <div class="grid gap-1.5">
                        <label for="email" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
                            Correo electrónico <span class="text-danger">*</span>
                        </label>
                        <input id="email" type="email" name="email" :value="user.email"
                            placeholder="cliente@ejemplo.com" autocomplete="email" required :class="[
                                'h-11 w-full rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs transition-[color,box-shadow,border-color] duration-150 outline-none placeholder:text-neutral-400',
                                errors.email
                                    ? 'border-danger focus-visible:border-danger focus-visible:ring-danger/20 focus-visible:ring-2'
                                    : 'border-neutral-300 focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/20 dark:border-neutral-600',
                            ]" />
                        <p v-if="errors.email" class="text-danger flex items-center gap-1 text-xs font-medium" role="alert">{{ errors.email }}</p>
                    </div>
                    <div v-if="mustVerifyEmail && !user.email_verified_at" class="border-warning/30 bg-warning/5 mt-4 rounded-lg border p-3">
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">
                            Tu correo no está verificado.
                            <Link :href="send()" as="button" class="hover:text-primary-600 font-medium text-primary underline underline-offset-2">
                                Reenviar email de verificación
                            </Link>
                        </p>
                        <p v-if="status === 'verification-link-sent'" class="text-success mt-1 text-xs font-medium">✓ Enlace enviado a tu correo.</p>
                    </div>
                </div>

                <!-- Datos personales -->
                <PersonalProfileSection :perfil="user.perfil_persona" rolNombre="cliente" :errors="errors" />

                <!-- Guardar -->
                <div class="flex items-center gap-4">
                    <Button type="submit" size="lg" :disabled="processing" class="hover:bg-primary-600 bg-primary">
                        <Spinner v-if="processing" />
                        Guardar cambios
                    </Button>
                    <span v-if="status === 'profile-updated'" class="text-success text-sm font-medium">✓ Cambios guardados</span>
                </div>
            </Form>

            <DeleteUser />
        </div>
    </PanelLayout>
</template>
