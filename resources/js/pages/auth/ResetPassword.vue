<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { update } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Restablecer contraseña',
        description: 'Ingresa tu nueva contraseña',
    },
});

const props = defineProps<{
    token: string;
    email: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>
    <Head title="Restablecer contraseña" />

    <div class="space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl font-bold">Restablecer contraseña</h1>
            <p class="text-sm text-muted-foreground">
                Ingresa tu nueva contraseña para restablecer el acceso a tu cuenta.
            </p>
        </div>

        <Form
            v-bind="update.form()"
            :transform="(data) => ({ ...data, token, email })"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Correo electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        autocomplete="email"
                        v-model="inputEmail"
                        readonly
                        disabled
                        class="bg-gray-50"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Contraseña</Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        autofocus
                        placeholder="Contraseña nueva"
                        required
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirmar contraseña</Label>
                    <PasswordInput
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Confirma tu contraseña"
                        required
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    size="lg"
                    class="mt-2 w-full"
                    :disabled="processing"
                    data-test="reset-password-button"
                >
                    <Spinner v-if="processing" />
                    Restablecer contraseña
                </Button>
            </div>
        </Form>

        <div class="pt-4 text-center text-sm text-muted-foreground">
            ¿Necesitas ayuda?
            <TextLink :href="login()" class="underline underline-offset-4">
                Vuelve al inicio
            </TextLink>
        </div>
    </div>
</template>