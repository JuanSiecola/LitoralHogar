import { ref } from 'vue'

interface ImagenesForm {
    imagenes: File[]
    imagen_principal_index: number
}

export function useImagenes(form: ImagenesForm) {
    const previews = ref<string[]>([])

    function onImagenesChange(event: Event) {
        const input = event.target as HTMLInputElement
        if (!input.files) return
        const nuevos = Array.from(input.files)
        form.imagenes = [...form.imagenes, ...nuevos]
        nuevos.forEach(file => previews.value.push(URL.createObjectURL(file)))
        input.value = ''
    }

    function eliminarImagen(index: number) {
        URL.revokeObjectURL(previews.value[index])
        form.imagenes = form.imagenes.filter((_, i) => i !== index)
        previews.value = previews.value.filter((_, i) => i !== index)
        if (form.imagen_principal_index >= form.imagenes.length) {
            form.imagen_principal_index = 0
        }
    }

    return { previews, onImagenesChange, eliminarImagen }
}
