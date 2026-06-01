import { Building2, Heart, LayoutDashboard, MessageSquare, Settings } from 'lucide-vue-next'
import type { Component } from 'vue'
import cliente from '@/routes/cliente'

export interface NavLink {
    label: string
    href: string
    icon: Component
}

export function useClienteNav(): NavLink[] {
    return [
        { label: 'Dashboard',     href: cliente.dashboard.url(),   icon: LayoutDashboard },
        { label: 'Propiedades',   href: cliente.propiedades.url(), icon: Building2 },
        { label: 'Mis Favoritos', href: cliente.favoritos.url(),   icon: Heart },
        { label: 'Mis Consultas', href: cliente.consultas.url(),   icon: MessageSquare },
        { label: 'Mi Perfil',     href: cliente.perfil.url(),      icon: Settings },
    ]
}
