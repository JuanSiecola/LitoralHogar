import { ref, watch, type Ref } from 'vue';
import type { OpcionUbicacion } from '@/types';

interface UseLocalidadesOptions {
    onChange?: () => void;
}

export function useLocalidades(
    departamentoIdRef: Ref<string>,
    options: UseLocalidadesOptions = {},
) {
    const localidades = ref<OpcionUbicacion[]>([]);
    const cargandoLocalidades = ref(false);

    async function cargar(departamentoId: number) {
        cargandoLocalidades.value = true;
        try {
            const res = await fetch(`/departamentos/${departamentoId}/localidades`, {
                headers: { Accept: 'application/json' },
            });
            const json = await res.json();
            localidades.value = json.data ?? [];
        } catch (e) {
            console.error('Error cargando localidades:', e);
            localidades.value = [];
        } finally {
            cargandoLocalidades.value = false;
        }
    }

    watch(
        departamentoIdRef,
        (nuevoId, anteriorId) => {
            if (nuevoId) {
                cargar(Number(nuevoId));
                if (anteriorId !== undefined && nuevoId !== anteriorId) {
                    options.onChange?.();
                }
            } else {
                localidades.value = [];
                if (anteriorId !== undefined) {
                    options.onChange?.();
                }
            }
        },
        { immediate: true },
    );

    return { localidades, cargandoLocalidades };
}
