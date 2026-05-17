import { Building2, LayoutDashboard, Plus, Settings } from 'lucide-vue-next'
import inmobiliaria from '@/routes/inmobiliaria' 
import type { Component } from 'vue'

export interface NavLink {
    label: string
    href: string
    icon: Component
}

export function useInmobiliariaNav(): NavLink[] {
    return [
        { label: 'Dashboard',       href: inmobiliaria.dashboard.url(),  icon: LayoutDashboard },
        { label: 'Mis Propiedades', href: inmobiliaria.propiedades.index.url(),  icon: Building2 },
        { label: 'Crear Propiedad', href: inmobiliaria.propiedades.create.url(),  icon: Plus },
        //{ label: 'Mi Perfil',       href: inmobiliaria.dashboard.perfil.url(),  icon: Settings },
    ]
}
