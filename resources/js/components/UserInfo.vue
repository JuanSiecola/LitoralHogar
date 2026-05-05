<script setup lang="ts">
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { User } from '@/types';

type Props = {
    user: User;
    showEmail?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    showEmail: false,
});

const { getInitials } = useInitials();

const fotoUrl = computed(() => props.user.perfil_persona?.foto_url ?? null);
const fullName = computed(() => `${props.user.perfil_persona?.nombre ?? props.user.nombre ?? ''} ${props.user.perfil_persona?.apellido ?? props.user.apellido ?? ''}`.trim());
</script>

<template>
    <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
        <AvatarImage v-if="fotoUrl" :src="fotoUrl" :alt="fullName" />
        <AvatarFallback class="rounded-lg text-black dark:text-white">
            {{ getInitials(fullName) }}
        </AvatarFallback>
    </Avatar>

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-medium">{{ fullName }}</span>
        <span v-if="showEmail" class="truncate text-xs text-muted-foreground">{{
            user.email
        }}</span>
    </div>
</template>
