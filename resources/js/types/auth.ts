export type User = {
    id: number;
    nombre: string;
    apellido: string;
    cedula: string;
    telefono: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    perfil_persona?: {
        nombre: string;
        apellido: string;
        cedula: string | null;
        telefono: string;
        foto_url: string | null;
    };
    inmobiliaria?: {
        razon_social: string;
        rut: string;
        direccion: string;
        telefono: string;
        logo_url: string | null;
    };
    rol_usuario?: {
        id: number;
        nombre: string;
    }[];
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
