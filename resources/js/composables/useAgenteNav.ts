import { Building2, LayoutDashboard, Plus, Settings } from 'lucide-vue-next'
import type { Component } from 'vue'

export interface NavLink {
    label: string
    href: string
    icon: Component
}

export function useAgenteNav(): NavLink[] {
    return [
        { label: 'Dashboard',       href: '#', icon: LayoutDashboard },
        { label: 'Mis Propiedades', href: '#', icon: Building2 },
        { label: 'Publicar',        href: '#', icon: Plus },
        { label: 'Mi Perfil',       href: '#', icon: Settings },
    ]
}
