<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { ChevronLeft } from 'lucide-vue-next';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { home } from '@/routes';
defineProps<{
    title?: string;
    description?: string;
}>();
</script>
<template>
    <div
        class="relative flex min-h-svh flex-col items-center justify-center gap-6 bg-muted p-6 md:p-10"
    >
        <Link
            :href="home()"
            class="absolute top-6 left-6 flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground"
        >
            <ChevronLeft class="h-4 w-4" />
            Volver al inicio
        </Link>

        <div class="flex w-full max-w-2xl flex-col gap-6">
            <div class="flex flex-col gap-6">
                <Card class="rounded-xl">
                    <CardHeader class="px-10 pt-8 pb-0 text-center">
                        <Link
                            :href="home()"
                            class="mb-4 inline-flex items-center justify-center"
                        >
                            <AppLogoIcon
                                class="h-28 w-auto text-black dark:text-white"
                            />
                        </Link>
                        <CardTitle class="text-xl">{{ title }}</CardTitle>
                        <CardDescription>
                            {{ description }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="px-10 pt-4 pb-8">
                        <slot />
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
