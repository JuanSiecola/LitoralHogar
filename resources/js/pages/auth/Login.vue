<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Inicia sesión en tu cuenta',
        description: 'Ingresa tus credenciales para acceder a tu cuenta.',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <Head title="Iniciar Sesión" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="email">Correo electrónico</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="email@example.com"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <Label for="password">Contraseña</Label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-sm"
                        :tabindex="5"
                    >
                        ¿Olvidaste tu contraseña?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Contraseña"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3">
                    <Checkbox id="remember" name="remember" :tabindex="3" />
                    <span>Recuérdame</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-4 w-full"
                size="lg"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" />
                Iniciar sesión
            </Button>
        </div>

        <div class="flex items-center gap-2 my-4">
    <div class="flex-1 h-px bg-gray-300"></div>
    <span class="text-sm text-gray-500">o</span>
    <div class="flex-1 h-px bg-gray-300"></div>
</div>

<a
    href="/auth/google"
    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 border rounded-lg shadow-sm bg-white hover:bg-gray-100 transition"
>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20">
        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.2 3.6l6.85-6.85C35.64 2.28 30.2 0 24 0 14.6 0 6.48 5.48 2.7 13.44l7.98 6.2C12.46 13.16 17.74 9.5 24 9.5z"/>
        <path fill="#4285F4" d="M46.1 24.5c0-1.64-.15-3.2-.42-4.7H24v9h12.4c-.54 2.9-2.2 5.36-4.68 7.02l7.2 5.6C43.98 37.36 46.1 31.4 46.1 24.5z"/>
        <path fill="#FBBC05" d="M10.68 28.64A14.5 14.5 0 0 1 9.5 24c0-1.62.28-3.2.78-4.64l-7.98-6.2A23.94 23.94 0 0 0 0 24c0 3.87.92 7.53 2.56 10.76l8.12-6.12z"/>
        <path fill="#34A853" d="M24 48c6.48 0 11.92-2.14 15.9-5.8l-7.2-5.6c-2 1.34-4.56 2.14-8.7 2.14-6.26 0-11.54-3.66-13.32-8.94l-8.12 6.12C6.48 42.52 14.6 48 24 48z"/>
    </svg>

    <span>Continuar con Google</span>
</a>

        <div
            class="text-center text-sm text-muted-foreground"
            v-if="canRegister"
        >
            ¿No tienes una cuenta?
            <TextLink :href="register()" :tabindex="5">Registrarme</TextLink>
        </div>
    </Form>
</template>
