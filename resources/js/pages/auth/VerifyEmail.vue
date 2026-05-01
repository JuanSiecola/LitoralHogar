<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { logout } from '@/routes';
import { send } from '@/routes/verification';

defineOptions({
    layout: {
        title: 'Verificar correo',
        description: 'Verifica tu dirección de correo electrónico para continuar',
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Verificar correo" />

    <div class="space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl font-bold">Verifica tu correo</h1>
            <p class="text-sm text-muted-foreground">
                Hemos enviado un enlace de verificación a tu correo electrónico.
                Por favor, haz clic en el enlace para continuar.
            </p>
        </div>

        <div
            v-if="status === 'verification-link-sent'"
            class="rounded-lg bg-green-50 p-4 text-center text-sm font-medium text-green-700 border border-green-200"
        >
            ✓ Se ha enviado un nuevo enlace de verificación a tu correo electrónico
        </div>

        <Form
            v-bind="send.form()"
            class="space-y-6"
            v-slot="{ processing }"
        >
            <div class="rounded-lg bg-blue-50 border border-blue-200 p-4 text-sm text-blue-700">
                <p class="font-medium mb-2">¿No recibiste el correo?</p>
                <p class="text-sm mb-4">
                    Podemos reenviar el enlace de verificación a tu dirección de correo.
                </p>
            </div>

            <Button 
                type="submit"
                size="lg"
                class="w-full"
                :disabled="processing"
                data-test="resend-verification-button"
            >
                <Spinner v-if="processing" />
                Reenviar correo de verificación
            </Button>
        </Form>

        <div class="space-y-2 pt-4">
            <Form method="post" :action="logout()">
                <TextLink 
                    as="button" 
                    type="submit"
                    href="#"
                    class="mx-auto block text-sm text-muted-foreground hover:text-foreground underline underline-offset-4"
                >
                    Cerrar sesión
                </TextLink>
            </Form>
        </div>
    </div>
</template>