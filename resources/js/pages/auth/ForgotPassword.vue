<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { email } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Recuperar contraseña',
        description: 'Ingresa tu correo para recibir un enlace de recuperación',
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Recuperar contraseña" />

    <div
        v-if="status"
        class="mb-4 rounded-lg bg-green-50 p-4 text-center text-sm font-medium text-green-700 border border-green-200"
    >
        {{ status }}
    </div>

    <div class="space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl font-bold">¿Olvidaste tu contraseña?</h1>
            <p class="text-sm text-muted-foreground">
                Ingresa tu correo electrónico y te enviaremos un enlace para recuperar tu contraseña.
            </p>
        </div>

        <Form v-bind="email.form()" v-slot="{ errors, processing }">
            <div class="grid gap-2">
                <Label for="email">Correo electrónico</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    autofocus
                    placeholder="tu@email.com"
                    required
                />
                <InputError :message="errors.email" />
            </div>

            <Button
                type="submit"
                class="mt-6 w-full"
                size="lg"
                :disabled="processing"
                data-test="email-password-reset-link-button"
            >
                <Spinner v-if="processing" />
                Enviar enlace de recuperación
            </Button>
        </Form>

        <div class="space-y-3 pt-4 text-center text-sm">
            <div class="text-muted-foreground">
                ¿Recordaste tu contraseña?
                <TextLink :href="login()" class="underline underline-offset-4">
                    Inicia sesión
                </TextLink>
            </div>
        </div>
    </div>
</template>