<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DeleteUser from '@/components/DeleteUser.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Profile settings',
                href: edit(),
            },
        ],
    },
});

interface User {
    nombre: string;
    apellido: string;
    cedula: string;
    telefono: string;
    email: string;
    email_verified_at: string | null;
}

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Profile settings" />

    <h1 class="sr-only">Ajustes de perfil</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Profile information"
            description="Update your user data"
        />

        <Form
            v-bind="ProfileController.update.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-4 md:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="nombre">Nombre</Label>
                    <Input
                        id="nombre"
                        class="mt-1 block w-full"
                        name="nombre"
                        :default-value="user.nombre"
                        required
                        autocomplete="given-name"
                        placeholder="Nombre"
                    />
                    <InputError class="mt-2" :message="errors.nombre" />
                </div>

                <div class="grid gap-2">
                    <Label for="apellido">Apellido</Label>
                    <Input
                        id="apellido"
                        class="mt-1 block w-full"
                        name="apellido"
                        :default-value="user.apellido"
                        required
                        autocomplete="family-name"
                        placeholder="Apellido"
                    />
                    <InputError class="mt-2" :message="errors.apellido" />
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="cedula">Cédula</Label>
                    <Input
                        id="cedula"
                        class="mt-1 block w-full"
                        name="cedula"
                        :default-value="user.cedula"
                        required
                        autocomplete="off"
                        placeholder="Cédula"
                    />
                    <InputError class="mt-2" :message="errors.cedula" />
                </div>

                <div class="grid gap-2">
                    <Label for="telefono">Teléfono</Label>
                    <Input
                        id="telefono"
                        class="mt-1 block w-full"
                        name="telefono"
                        :default-value="user.telefono"
                        required
                        autocomplete="tel"
                        placeholder="Teléfono"
                    />
                    <InputError class="mt-2" :message="errors.telefono" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="email">Correo electrónico</Label>
                <Input
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    name="email"
                    :default-value="user.email"
                    required
                    autocomplete="email"
                    placeholder="Correo electrónico"
                />
                <InputError class="mt-2" :message="errors.email" />
            </div>

            <div v-if="mustVerifyEmail && !user.email_verified_at">
                <p class="-mt-4 text-sm text-muted-foreground">
                    Tu correo no está verificado.
                    <Link
                        :href="send()"
                        as="button"
                        class="text-foreground underline decoration-neutral-300 underline-offset-4 transition hover:decoration-current"
                    >
                        Haz clic aquí para reenviar el email de verificación.
                    </Link>
                </p>

                <div
                    v-if="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    Se ha enviado un nuevo enlace de verificación a tu correo.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="processing" data-test="update-profile-button">
                    Guardar cambios
                </Button>
            </div>
        </Form>
    </div>

    <DeleteUser />
</template>