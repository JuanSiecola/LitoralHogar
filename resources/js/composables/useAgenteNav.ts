import { Building2, LayoutDashboard, MessageSquare, Plus, Settings } from 'lucide-vue-next'
import type { Component } from 'vue'
import agente from '@/routes/agente'

export interface NavLink {
    label: string
    href: string
    icon: Component
}

export function useAgenteNav(): NavLink[] {
    return [
        { label: 'Dashboard',       href: agente.dashboard.url(),           icon: LayoutDashboard },
        { label: 'Estadísticas',    href: agente.estadisticas.url(), icon: Building2 },
        { label: 'Mis Propiedades', href: agente.propiedades.url(),  icon: Building2 },
        { label: 'Crear Propiedad', href: agente.propiedades.create.url(),  icon: Plus },
        { label: 'Consultas Recibidas',       href: agente.consultas.url(),  icon: MessageSquare },
        { label: 'Mi Perfil',       href: agente.perfil.url(),              icon: Settings },
    ]
}
