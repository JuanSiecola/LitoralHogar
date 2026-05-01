<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import FileUploadInput from '@/components/FileUploadInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { useRegisterForm } from '@/composables/useRegisterForm';

const { selectedRole, isInmobiliariaSelected } = useRegisterForm();

defineOptions({
    layout: {
        title: 'Crear cuenta',
        description: 'Ingresa tus datos para crear una cuenta en LitoralHogar.',
    },
});

interface Role {
    id: number;
    nombre: string;
}

defineProps<{
    roles: Role[];
}>();
</script>

<template>
    <Head title="Crear cuenta" />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="cedula">Cédula</Label>
                <Input
                    id="cedula"
                    type="text"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="cedula"
                    name="cedula"
                    placeholder="12345678"
                />
                <InputError :message="errors.cedula" />
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="nombre">Nombre</Label>
                    <Input
                        id="nombre"
                        type="text"
                        required
                        :tabindex="2"
                        autocomplete="given-name"
                        name="nombre"
                        placeholder="Juan"
                    />
                    <InputError :message="errors.nombre" />
                </div>
                <div class="grid gap-2">
                    <Label for="apellido">Apellido</Label>
                    <Input
                        id="apellido"
                        type="text"
                        required
                        :tabindex="3"
                        autocomplete="family-name"
                        name="apellido"
                        placeholder="Pérez"
                    />
                    <InputError :message="errors.apellido" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="email">Correo electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="4"
                        autocomplete="email"
                        name="email"
                        placeholder="juanperez@gmail.com"
                    />
                    <InputError :message="errors.email" />
                </div>
                <div class="grid gap-2">
                    <Label for="telefono">Teléfono</Label>
                    <Input
                        id="telefono"
                        type="text"
                        required
                        :tabindex="5"
                        autocomplete="tel"
                        name="telefono"
                        placeholder="092247856"
                    />
                    <InputError :message="errors.telefono" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="roles">Tipo de usuario</Label>
                <select
                    id="roles"
                    v-model="selectedRole"
                    name="roles"
                    required
                    :tabindex="6"
                    class="rounded border border-gray-300 px-3 py-2"
                >
                    <option value="">Selecciona un tipo de usuario</option>
                    <option
                        v-for="role in roles"
                        :key="role.id"
                        :value="role.id"
                    >
                        {{ role.nombre }}
                    </option>
                </select>
                <InputError :message="errors.roles" />
            </div>

            <template v-if="isInmobiliariaSelected">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="razon_social">Razón Social</Label>
                        <Input
                            id="razon_social"
                            type="text"
                            required
                            :tabindex="7"
                            name="razon_social"
                            placeholder="Nombre de la inmobiliaria"
                        />
                        <InputError :message="errors.razon_social" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="rut">RUT</Label>
                        <Input
                            id="rut"
                            type="text"
                            required
                            :tabindex="8"
                            name="rut"
                            placeholder="12345678-9"
                        />
                        <InputError :message="errors.rut" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="direccion">Dirección</Label>
                    <Input
                        id="direccion"
                        type="text"
                        required
                        :tabindex="9"
                        name="direccion"
                        placeholder="Calle Principal 123"
                    />
                    <InputError :message="errors.direccion" />
                </div>

                <FileUploadInput
                    label="Logo"
                    name="logo"
                    accept="image/*"
                    :tabindex="10"
                    :errors="errors.logo"
                />
            </template>

            <div class="grid gap-2">
                <Label for="password">Contraseña</Label>
                <PasswordInput
                    id="password"
                    required
                    :tabindex="11"
                    autocomplete="new-password"
                    name="password"
                    placeholder="Contraseña"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirmar contraseña</Label>
                <PasswordInput
                    id="password_confirmation"
                    required
                    :tabindex="12"
                    autocomplete="new-password"
                    name="password_confirmation"
                    placeholder="Confirmar contraseña"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                size="lg"
                class="mt-2 w-full"
                :tabindex="13"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" />
                Crear cuenta
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            ¿Ya tienes una cuenta?
            <TextLink
                :href="login()"
                class="underline underline-offset-4"
                :tabindex="14"
                >Iniciar sesión</TextLink
            >
        </div>
    </Form>
</template>