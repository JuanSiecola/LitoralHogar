<script setup lang="ts">
import { computed, ref } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import { ChevronRight, LogOut, Menu, X } from 'lucide-vue-next'
import { logout } from '@/routes'
import { useUserDisplay } from '@/composables/useUserDisplay'
import type { NavLink } from '@/composables/useInmobiliariaNav'

defineProps<{ navLinks: NavLink[] }>()

const page = usePage()
const sidebarOpen = ref(false)
const user = computed(() => page.props.auth.user)
const { displayName, initials: displayInitial, avatarUrl } = useUserDisplay(() => page.props.auth.user)

function isActive(href: string): boolean {
    if (href === '#') return false
    return window.location.pathname === href
}

function logoutUser() {
    router.post(logout.url())
}
</script>

<template>
    <div class="h-screen bg-muted flex overflow-hidden">

        <div v-if="sidebarOpen" class="fixed inset-0 z-20 bg-black/40 lg:hidden" @click="sidebarOpen = false" />

        <aside :class="[
            'fixed inset-y-0 left-0 z-30 w-68 bg-sidebar border-r border-sidebar-border flex flex-col transition-transform duration-300 lg:translate-x-0 lg:static lg:z-auto',
            sidebarOpen ? 'translate-x-0' : '-translate-x-full'
        ]">
            <div class="h-16 flex items-center px-6 border-b border-sidebar-border shrink-0">
                <img src="/logo-horizontal.svg" alt="Logo" class="h-12 w-auto">
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <template v-for="link in navLinks" :key="link.label">
                    <Link :href="link.href" :class="[
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group',
                        isActive(link.href)
                            ? 'bg-sidebar-accent text-sidebar-primary'
                            : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground'
                    ]">
                        <component :is="link.icon"
                            :class="['w-4 h-4 shrink-0', isActive(link.href) ? 'text-sidebar-primary' : 'text-muted-foreground group-hover:text-sidebar-foreground']" />
                        {{ link.label }}
                        <ChevronRight v-if="isActive(link.href)" class="w-3 h-3 ml-auto text-sidebar-primary/60" />
                    </Link>
                </template>
            </nav>

            <div class="border-t border-sidebar-border p-4 shrink-0">
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-8 h-8 rounded-full bg-sidebar-accent flex items-center justify-center shrink-0 overflow-hidden">
                        <img v-if="avatarUrl" :src="avatarUrl" class="w-full h-full object-cover" alt="avatar" />
                        <span v-else class="text-xs font-semibold text-sidebar-primary">
                            {{ displayInitial }}
                        </span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-sidebar-foreground truncate">{{ displayName }}</p>
                        <p class="text-xs text-muted-foreground truncate">{{ user?.email }}</p>
                    </div>
                </div>
                <button @click="logoutUser"
                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-muted-foreground hover:text-destructive hover:bg-destructive/10 rounded-lg transition-colors">
                    <LogOut class="w-4 h-4" />
                    Cerrar sesión
                </button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="lg:hidden h-16 bg-card border-b border-border flex items-center px-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-muted-foreground hover:bg-muted">
                    <Menu v-if="!sidebarOpen" class="w-5 h-5" />
                    <X v-else class="w-5 h-5" />
                </button>

            </header>

            <main class="flex-1 p-6 overflow-auto">
                <slot />
            </main>
        </div>

    </div>
</template>