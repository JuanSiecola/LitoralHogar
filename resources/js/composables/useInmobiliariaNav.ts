import { Building2, LayoutDashboard, Plus, Settings } from 'lucide-vue-next'
import { dashboard } from '@/routes/inmobiliaria'
import type { Component } from 'vue'

export interface NavLink {
    label: string
    href: string
    icon: Component
}

export function useInmobiliariaNav(): NavLink[] {
    return [
        { label: 'Dashboard',       href: dashboard.url(),  icon: LayoutDashboard },
        { label: 'Mis Propiedades', href: '#',               icon: Building2 },
        { label: 'Crear Propiedad', href: '#',               icon: Plus },
        { label: 'Mi Perfil',       href: '#',               icon: Settings },
    ]
}
