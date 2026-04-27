# Litoral Hogar Project PHP Laravel 

Este es un proyecto en laravel para la gestión de propiedades. Desarrollado para el taller de aplicaciones web con php, el proyecto incluye funcionalidades para la gestión de usuarios, propiedades, roles y favoritos.

## Tecnologías utilizadas
- PHP en backend
- Laravel como framework de desarrollo
- MySQL como sistema de gestión de bases de datos
- Vue.js con inertia.js para el frontend
- Tailwind CSS para el diseño de la interfaz de usuario
- Filament para el panel de administración
- Git para el control de versiones

## Pasos para ejecutar el proyecto
1. Clonar el repositorio en tu máquina local.
2. Instalar las dependencias utilizando Composer:
   ```
   composer install
   ```
3. Instalar las dependencias del frontend utilizando npm:
   ```
   npm install
   ```
4. Crear la base de datos manualmente en phpMyAdmin con el nombre `litoralhogar`.
5. Configurar las variables de entorno en el archivo `.env` con este contenido:
   ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=litoralhogar
    DB_USERNAME=root
    DB_PASSWORD=
    ```
6. Ejecutar las migraciones para crear las tablas en la base de datos:
   ```
   php artisan migrate
   ```
7. Ejecutar el servidor de desarrollo:
   ```
   composer run dev
   ```
