import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

interface Role {
    id: number;
    nombre: string;
    activo: boolean;
}

export function useRegisterForm() {
    const page = usePage();
    const selectedRole = ref<number | string>('');

    const roles = computed((): Role[] => {
        return (page.props.roles as Role[]) || [];
    });

    const getRoleById = (id: number | string): Role | undefined => {
        return roles.value.find(role => role.id == id);
    };

    const checkRoleType = (roleType: string): boolean => {
        const role = getRoleById(selectedRole.value);
        return role?.nombre.toLowerCase().includes(roleType.toLowerCase()) || false;
    };

    const isInmobiliariaSelected = computed((): boolean => {
        return checkRoleType('inmobiliaria');
    });

    const isAgenteSelected = computed((): boolean => {
        return checkRoleType('agente');
    });

    const isClienteSelected = computed((): boolean => {
        return checkRoleType('cliente');
    });

    const getSelectedRoleName = computed((): string => {
        const role = getRoleById(selectedRole.value);
        return role?.nombre || '';
    });

    return {
        selectedRole,
        roles,
        getRoleById,
        isInmobiliariaSelected,
        isAgenteSelected,
        isClienteSelected,
        getSelectedRoleName,
    };
}