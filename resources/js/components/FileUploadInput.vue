<script setup lang="ts">
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';

interface Props {
    label: string;
    name: string;
    accept?: string;
    errors?: string;
    tabindex?: number;
}

const props = withDefaults(defineProps<Props>(), {
    accept: 'image/*',
    tabindex: 4,
});

const fileInput = ref<HTMLInputElement>();
const fileName = ref<string>('No file selected');
const preview = ref<string>('');

const handleFileSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        fileName.value = file.name;

        // Crear preview si es imagen
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const handleDragOver = (event: DragEvent) => {
    event.preventDefault();
    event.stopPropagation();
};

const handleDrop = (event: DragEvent) => {
    event.preventDefault();
    event.stopPropagation();

    const files = event.dataTransfer?.files;
    if (files?.[0] && fileInput.value) {
        fileInput.value.files = files;
        handleFileSelect({ target: fileInput.value } as any);
    }
};

const openFileDialog = () => {
    fileInput.value?.click();
};

const clearFile = () => {
    fileName.value = 'No file selected';
    preview.value = '';
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};
</script>

<template>
    <div class="grid gap-2">
        <Label :for="`file-${name}`">{{ label }}</Label>

        <div
            class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer transition hover:border-blue-400 hover:bg-blue-50"
            @dragover="handleDragOver"
            @drop="handleDrop"
            @click="openFileDialog"
        >
            <input
                ref="fileInput"
                :id="`file-${name}`"
                type="file"
                :name="name"
                :accept="accept"
                :tabindex="tabindex"
                class="hidden"
                @change="handleFileSelect"
            />

            <!-- Preview de imagen -->
            <div v-if="preview" class="mb-4">
                <img :src="preview" alt="Preview" class="h-32 w-full object-cover rounded-md" />
            </div>

            <!-- Contenido cuando no hay archivo -->
            <div v-if="!preview" class="space-y-2">
                <svg
                    class="mx-auto h-12 w-12 text-gray-400"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 48 48"
                >
                    <path
                        d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                    <path
                        d="M32 4v12m-6-6h12"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
                <p class="text-sm font-medium text-gray-700">
                    Arrastra tu imagen aquí o
                    <span class="text-blue-600 font-semibold">selecciona un archivo</span>
                </p>
                <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 2MB</p>
            </div>

            <!-- Contenido cuando hay archivo -->
            <div v-else class="space-y-2">
                <svg
                    class="mx-auto h-12 w-12 text-green-500"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                    />
                </svg>
                <p class="text-sm font-medium text-gray-700">{{ fileName }}</p>
                <button
                    type="button"
                    @click.stop="clearFile"
                    class="text-xs text-red-600 hover:text-red-800 font-medium"
                >
                    Cambiar archivo
                </button>
            </div>
        </div>

        <InputError :message="errors" />
    </div>
</template>