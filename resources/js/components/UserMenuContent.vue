<script setup lang="ts">
import { Link, router} from '@inertiajs/vue3';
import {computed} from 'vue';
import {LogOut,Settings,LayoutDashboard} from 'lucide-vue-next';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import UserInfo from '@/components/UserInfo.vue';
import { logout } from '@/routes';
import { view } from '@/routes/profile';
import type { User } from '@/types';

type Props = {
    user: User;
};
const props = defineProps<Props>();
const {user} = props;

const getDashboardLink = computed(() => {
    if (!user.rol_usuario || user.rol_usuario.length === 0){
    return null;
}
    const rolNombre = user.rol_usuario[0].nombre.toLowerCase();
    
    if (rolNombre === 'cliente') {
        return '/cliente/dashboard';
    } 
    else if (rolNombre === 'inmobiliaria') {
        return '/inmobiliaria/dashboard';
    } 
    else if (rolNombre === 'agente') {
        return '/agente/dashboard';
    }
    
    return null;
});

const handleLogout = () => {
    router.flushAll();
};

</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    
    <DropdownMenuGroup v-if="getDashboardLink" >
        <DropdownMenuItem :as-child="true">
            <Link
                class="block w-full cursor-pointer"
                :href="getDashboardLink"
                prefetch
            >
                <LayoutDashboard class="mr-2 h-4 w-4" />
                Mi panel
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator v-if="getDashboardLink" />

    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full cursor-pointer" :href="view()" prefetch>
                <Settings class="mr-2 h-4 w-4" />
                Configuración
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem :as-child="true">
        <Link
            class="block w-full cursor-pointer"
            :href="logout()"
            @click="handleLogout"
            as="button"
            data-test="logout-button"
        >
            <LogOut class="mr-2 h-4 w-4" />
            Cerrar sesión
        </Link>
    </DropdownMenuItem>
</template>
