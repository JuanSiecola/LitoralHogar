import { computed, toValue, type MaybeRefOrGetter } from 'vue';
import { getInitials } from '@/composables/useInitials';
import type { User } from '@/types';

export function useUserDisplay(userRef: MaybeRefOrGetter<User>) {
    const isInmobiliaria = computed(() =>
        toValue(userRef).rol_usuario?.some((r) => r.nombre.toLowerCase().includes('inmobiliaria')) ?? false,
    );

    const avatarUrl = computed(() => {
        const user = toValue(userRef);
        return isInmobiliaria.value ? (user.inmobiliaria?.logo_url ?? null) : (user.perfil_persona?.foto_url ?? null);
    });

    const displayName = computed(() => {
        const user = toValue(userRef);
        if (isInmobiliaria.value) return user.inmobiliaria?.razon_social ?? '';
        const p = user.perfil_persona;
        return p ? `${p.nombre} ${p.apellido}`.trim() : user.email;
    });

    const initials = computed(() => getInitials(displayName.value));

    return { avatarUrl, displayName, initials, isInmobiliaria };
}
