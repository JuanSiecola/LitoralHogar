export interface Propiedad {
    id: number;
    titulo: string;
    tipo_operacion: 'Venta' | 'Alquiler';
    tipo_propiedad: string;
    precio: number;
    nro_habitaciones: number;
    nro_banios: number;
    superficie_total: number;
    localidad: string;
    departamento: string;
    latitud?: number | string | null;
    longitud?: number | string | null;
    imagen_url?: string | null;
}

export interface OpcionUbicacion {
    id: number;
    nombre: string;
}

export interface PropiedadPaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginatedPropiedades {
    data: Propiedad[];
    links: PropiedadPaginationLink[];
    current_page: number;
    last_page: number;
    from: number | null;
    to: number | null;
    total: number;
    per_page: number;
}

export interface PropiedadFilters {
    tipo_operacion?: string;
    tipo_propiedad?: string;
    departamento_id?: string | number;
    localidad_id?: string | number;
    nro_habitaciones?: string | number;
    nro_banios?: string | number;
    precio_min?: string | number;
    precio_max?: string | number;
    superficie_min?: string | number;
    per_page?: string | number;
}
