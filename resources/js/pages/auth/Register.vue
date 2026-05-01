<script setup lang="ts">
import { ref } from 'vue';
import { Form, Head } from '@inertiajs/vue3';
import { Building2, Briefcase, KeyRound } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import FileUpload from '@/components/FileUpload.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineOptions({
    layout: {
        title: 'Crear cuenta',
        description: 'Ingresa tus datos para crear una cuenta en LitoralHogar.',
    },
});

type TipoUsuario = 'inmobiliaria' | 'agente' | 'cliente';

const paso = ref<1 | 2>(1);
const tipo = ref<TipoUsuario | null>(null);

const opciones = [
    {
        valor: 'inmobiliaria' as TipoUsuario,
        icono: Building2,
        titulo: 'Inmobiliaria',
        descripcion: 'Representás una agencia o empresa inmobiliaria.',
    },
    {
        valor: 'agente' as TipoUsuario,
        icono: Briefcase,
        titulo: 'Agente Independiente',
        descripcion: 'Trabajás de forma independiente publicando y gestionando propiedades.',
    },
    {
        valor: 'cliente' as TipoUsuario,
        icono: KeyRound,
        titulo: 'Cliente',
        descripcion: 'Buscás propiedades para comprar o alquilar.',
    },
];

function elegirTipo(t: TipoUsuario) {
    tipo.value = t;
    paso.value = 2;
}

function volver() {
    paso.value = 1;
    tipo.value = null;
}
</script>

<template>
    <Head title="Crear cuenta" />

    <!-- Paso 1: Selección de tipo -->
    <div v-if="paso === 1" class="flex flex-col gap-4">
        <div class="flex flex-col items-center gap-2 text-center">
            <h1 class="text-2xl font-bold">¿Cómo querés registrarte?</h1>
            <p class="text-sm text-muted-foreground">Elegí el tipo de cuenta que mejor te describe.</p>
        </div>

        <div class="grid grid-cols-1 gap-3">
            <button
                v-for="opcion in opciones"
                :key="opcion.valor"
                type="button"
                @click="elegirTipo(opcion.valor)"
                class="flex items-center gap-4 rounded-lg border p-5 text-left transition-colors hover:border-primary hover:bg-muted"
            >
                <component :is="opcion.icono" class="size-6 shrink-0 text-primary" />
                <div>
                    <p class="font-semibold">{{ opcion.titulo }}</p>
                    <p class="text-sm text-muted-foreground">{{ opcion.descripcion }}</p>
                </div>
            </button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            ¿Ya tenés una cuenta?
            <TextLink :href="login()" class="underline underline-offset-4">Iniciar sesión</TextLink>
        </div>
    </div>

    <!-- Paso 2: Formulario según tipo -->
    <Form
        v-else
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        enctype="multipart/form-data"
        class="flex flex-col gap-6"
    >
        <input type="hidden" name="tipo" :value="tipo" />

        <div class="grid gap-6">

            <!-- Campos exclusivos de inmobiliaria -->
            <template v-if="tipo === 'inmobiliaria'">
                <div class="grid gap-2">
                    <Label for="razon_social">Razón Social</Label>
                    <Input
                        id="razon_social"
                        type="text"
                        required
                        autofocus
                        name="razon_social"
                        placeholder="Inmobiliaria XYZ S.A."
                    />
                    <InputError :message="errors.razon_social" />
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="rut">RUT</Label>
                        <Input
                            id="rut"
                            type="text"
                            required
                            name="rut"
                            placeholder="211234560010"
                        />
                        <InputError :message="errors.rut" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="direccion">Dirección</Label>
                        <Input
                            id="direccion"
                            type="text"
                            required
                            name="direccion"
                            placeholder="Av. 18 de Julio 1234"
                        />
                        <InputError :message="errors.direccion" />
                    </div>
                </div>

                <FileUpload
                    label="Logo (archivo, max 2MB)"
                    name="logo_url"
                    :errors="errors.logo_url"
                />
            </template>

            <!-- Nombre y Apellido (solo agente y cliente) -->
            <div v-if="tipo !== 'inmobiliaria'" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="nombre">Nombre</Label>
                    <Input
                        id="nombre"
                        type="text"
                        required
                        autofocus
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
                        autocomplete="family-name"
                        name="apellido"
                        placeholder="Pérez"
                    />
                    <InputError :message="errors.apellido" />
                </div>
            </div>

            <!-- Cédula + Teléfono (solo agente y cliente) -->
            <div v-if="tipo !== 'inmobiliaria'" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="cedula">Cédula</Label>
                    <Input
                        id="cedula"
                        type="text"
                        required
                        autocomplete="off"
                        name="cedula"
                        placeholder="12345678"
                    />
                    <InputError :message="errors.cedula" />
                </div>
                <div class="grid gap-2">
                    <Label for="telefono">Teléfono</Label>
                    <Input
                        id="telefono"
                        type="text"
                        required
                        autocomplete="tel"
                        name="telefono"
                        placeholder="092247856"
                    />
                    <InputError :message="errors.telefono" />
                </div>
            </div>

            <!-- Email (agente y cliente: ancho completo) -->
            <div v-if="tipo !== 'inmobiliaria'" class="grid gap-2">
                <Label for="email">Correo electrónico</Label>
                <Input
                    id="email"
                    type="email"
                    required
                    autocomplete="email"
                    name="email"
                    placeholder="juanperez@gmail.com"
                />
                <InputError :message="errors.email" />
            </div>

            <!-- Email y Teléfono (inmobiliaria: juntos en 2 columnas) -->
            <div v-if="tipo === 'inmobiliaria'" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="email">Correo electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        required
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
                        autocomplete="tel"
                        name="telefono"
                        placeholder="092247856"
                    />
                    <InputError :message="errors.telefono" />
                </div>
            </div>

            <!-- Checkbox agente (solo cliente) -->
            <div v-if="tipo === 'cliente'" class="flex items-center gap-2">
                <input
                    id="es_agente"
                    type="checkbox"
                    name="es_agente"
                    value="1"
                    class="h-4 w-4 rounded border-gray-300"
                />
                <Label for="es_agente" class="cursor-pointer font-normal">
                    También quiero ofrecer propiedades como agente
                </Label>
            </div>

            <!-- Contraseña (todos) -->
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="password">Contraseña</Label>
                    <PasswordInput
                        id="password"
                        required
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
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirmar contraseña"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <Button
                    type="button"
                    variant="outline"
                    size="lg"
                    @click="volver"
                >
                    Volver
                </Button>
                <Button
                    type="submit"
                    size="lg"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" />
                    Crear cuenta
                </Button>
            </div>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            ¿Ya tenés una cuenta?
            <TextLink :href="login()" class="underline underline-offset-4">Iniciar sesión</TextLink>
        </div>
    </Form>
</template>
