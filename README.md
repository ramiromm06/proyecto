# GestorPro

Proyecto final de INF560 — Desarrollo Web Backend (UATF). Aplicación web monolítica en Laravel para
gestión de proyectos colaborativos: equipos crean proyectos, gestionan tareas con estados y prioridades,
y los miembros colaboran mediante comentarios. Incluye autenticación por sesión, control de acceso por
roles y permisos (spatie/laravel-permission) y autorización por pertenencia (Policies).

## Stack tecnológico

- Laravel 13 / PHP 8.3+
- PostgreSQL
- Autenticación por sesión con Laravel Breeze (stack Blade)
- spatie/laravel-permission para roles y permisos
- Blade + Tailwind CSS

## Instalación

1. Clonar el repositorio e instalar dependencias:

   ```bash
   composer install
   npm install
   ```

2. Copiar `.env.example` a `.env` y configurar la base de datos PostgreSQL:

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=gestorpro
   DB_USERNAME=postgres
   DB_PASSWORD=tu_password
   ```

3. Crear la base de datos `gestorpro` (por ejemplo con `psql -U postgres -c "CREATE DATABASE gestorpro;"`).

4. Generar la clave de la aplicación, migrar y sembrar datos de prueba:

   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

5. Compilar los assets y levantar el servidor:

   ```bash
   npm run build
   php artisan serve
   ```

   La aplicación queda disponible en `http://localhost:8000`.

> Nota: `spatie/laravel-permission` cachea los roles y permisos. Si vuelves a sembrar la base de datos
> con `migrate:fresh --seed` y notas comportamientos de autorización inconsistentes, limpia la caché con
> `php artisan permission:cache-reset`.

## Credenciales semilla

El seeder (`database/seeders/DatabaseSeeder.php`) crea un usuario por cada rol para poder probar el
sistema completo desde la interfaz. La contraseña de todos los usuarios de prueba es `password`.

| Rol         | Email                       | Contraseña |
|-------------|------------------------------|------------|
| Admin       | `admin@gestorpro.test`       | `password` |
| Líder       | `lider@gestorpro.test`       | `password` |
| Colaborador | `colaborador@gestorpro.test` | `password` |
| Invitado    | `invitado@gestorpro.test`    | `password` |

Además se generan usuarios, proyectos, tareas y comentarios aleatorios para poblar los listados.

## Roles y permisos

Los permisos se gestionan con `spatie/laravel-permission`. La autorización combina dos capas:

- **Permiso por rol**: qué acciones puede intentar un rol (ej. "crear proyecto"), verificado con `$user->can(...)`.
- **Autorización por pertenencia**: si el usuario puede actuar sobre ESE recurso en particular (ej. solo el
  dueño puede editar su proyecto), verificado con Policies (`ProjectPolicy`, `TaskPolicy`, `CommentPolicy`).

| Rol         | Permisos representativos |
|-------------|---------------------------|
| Admin       | Gestiona usuarios y roles; ve, edita y elimina cualquier proyecto o tarea del sistema. |
| Líder       | Crea proyectos; gestiona miembros de sus proyectos; crea, edita, asigna y elimina tareas de sus proyectos. |
| Colaborador | Ve los proyectos donde es miembro; crea y edita únicamente las tareas asignadas a él; comenta. |
| Invitado    | Acceso de solo lectura a los proyectos donde fue agregado; puede comentar. |

## Modelo de datos

`User` — `Project` (1:N vía `owner_id`, N:M vía tabla pivote `project_user` con el campo `project_role`) —
`Task` (N:1 con `Project` y con `User` como responsable) — `Comment` (N:1 con `Task` y `User`) — `Label`
(N:M con `Task`). `Project` y `Task` usan soft deletes.

## Fases de entrega (tags de Git)

| Tag | Contenido |
|-----|-----------|
| `v0.1` | Migraciones, modelos, relaciones, factories y seeders del dominio. |
| `v0.2` | Autenticación por sesión (Laravel Breeze), CSRF, protección de rutas, layout privado. |
| `v0.3` | spatie/laravel-permission: roles, permisos, Policies y control de acceso en Blade. |
| `v0.4` | CRUD completo: proyectos, tareas anidadas, comentarios, miembros (N:M), Form Requests, soft delete. |
| `v1.0` | Filtros, paginación, página de error 403 personalizada, documentación. |
