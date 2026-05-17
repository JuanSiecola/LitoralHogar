<script setup lang="ts">
import { computed, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';

interface Props {
    label: string;
    name: string;
    accept?: string;
    errors?: string;
    tabindex?: number;
    maxSizeMb?: number;
}

const props = withDefaults(defineProps<Props>(), {
    accept: 'image/*',
    tabindex: 0,
    maxSizeMb: 2,
});

const fileInput = ref<HTMLInputElement | null>(null);
const fileName = ref<string>('No file selected');
const preview = ref<string>('');
const fileError = ref<string>('');

const maxBytes = computed(() => props.maxSizeMb * 1024 * 1024);
const errorMessage = computed(() => fileError.value || props.errors || '');

const setPreview = (file: File) => {
    if (!file.type.startsWith('image/')) {
        preview.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        preview.value = (e.target?.result as string) || '';
    };
    reader.readAsDataURL(file);
};

const resetFile = () => {
    fileName.value = 'No file selected';
    preview.value = '';
    fileError.value = '';
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const applyFile = (file: File) => {
    if (file.size > maxBytes.value) {
        resetFile();
        fileError.value = `El archivo debe pesar menos de ${props.maxSizeMb}MB.`;
        return;
    }

    fileError.value = '';
    fileName.value = file.name;
    setPreview(file);
};

const handleFileSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (!file) {
        resetFile();
        return;
    }

    applyFile(file);
};

const handleDragOver = (event: DragEvent) => {
    event.preventDefault();
    event.stopPropagation();
};

const handleDrop = (event: DragEvent) => {
    event.preventDefault();
    event.stopPropagation();

    const file = event.dataTransfer?.files?.[0];
    if (!file) {
        return;
    }

    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);

    if (fileInput.value) {
        fileInput.value.files = dataTransfer.files;
    }

    applyFile(file);
};

const openFileDialog = () => {
    fileInput.value?.click();
};

const clearFile = () => {
    resetFile();
};
</script>

<template>
    <div class="grid gap-2">
        <Label :for="`file-${name}`">{{ label }}</Label>

        <div
            class="group relative flex cursor-pointer flex-col gap-3 rounded-lg border-2 border-dashed border-muted-foreground/40 bg-muted/20 p-5 text-center transition hover:border-primary/60 hover:bg-muted/40"
            @dragover="handleDragOver"
            @drop="handleDrop"
            @click="openFileDialog"
        >
            <input
                ref="fileInput"
                :id="`file-${name}`"
                type="file"
                :name="name"
                accept="image/jpg,image/jpeg,image/png,image/webp"
                :tabindex="tabindex"
                class="hidden"
                @change="handleFileSelect"
            />

            <div v-if="preview" class="overflow-hidden rounded-md border border-muted-foreground/20">
                <img :src="preview" alt="Preview" class="h-36 w-full object-cover" />
            </div>

            <div v-if="!preview" class="space-y-1">
                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary">
                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M12 16V4m0 0l-4 4m4-4l4 4"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        <path
                            d="M20 16v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>
                <p class="text-sm font-medium text-foreground">
                    Arrastra tu logo o
                    <span class="font-semibold text-primary">selecciona un archivo</span>
                </p>
                <p class="text-xs text-muted-foreground">
                    PNG, JPG o WEBP hasta {{ props.maxSizeMb }}MB
                </p>
            </div>

            <div v-else class="space-y-1">
                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-600">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <p class="text-sm font-medium text-foreground">{{ fileName }}</p>
                <button
                    type="button"
                    @click.stop="clearFile"
                    class="text-xs font-semibold text-primary hover:text-primary/80"
                >
                    Cambiar archivo
                </button>
            </div>
        </div>

        <InputError :message="errorMessage" />
    </div>
</template>
