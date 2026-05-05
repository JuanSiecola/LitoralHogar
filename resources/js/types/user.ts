export interface Rol {
    id: number;
    nombre: string;
}

export interface PerfilPersona {
    nombre: string;
    apellido: string;
    cedula: string | null;
    telefono: string;
    foto_url: string | null;
}

export interface InmobiliariaData {
    razon_social: string;
    rut: string;
    direccion: string;
    telefono: string;
    logo_url: string | null;
}

export interface User {
    id: number;
    email: string;
    email_verified_at: string | null;
    perfil_persona?: PerfilPersona;
    inmobiliaria?: InmobiliariaData;
    rol_usuario?: Rol[];
}
