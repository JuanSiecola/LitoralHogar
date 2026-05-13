<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { useUserDisplay } from '@/composables/useUserDisplay';
import type { User } from '@/types';

const props = defineProps<{ user: User }>();

const { avatarUrl, displayName, initials } = useUserDisplay(props.user);
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <button
                class="relative size-10 overflow-hidden rounded-full border border-border focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
            >
                <Avatar class="size-10">
                    <AvatarImage
                        v-if="avatarUrl"
                        :src="avatarUrl"
                        :alt="displayName"
                    />
                    <AvatarFallback
                        class="bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white"
                    >
                        {{ initials }}
                    </AvatarFallback>
                </Avatar>
            </button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-72">
            <UserMenuContent :user="user" />
        </DropdownMenuContent>
    </DropdownMenu>
</template>
