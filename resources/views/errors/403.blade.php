<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Acceso no autorizado — {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 text-center">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Error 403</p>
            <h1 class="mt-2 text-2xl font-bold text-gray-800">No tienes permiso para realizar esta acción</h1>
            <p class="mt-2 text-gray-600 max-w-md">
                Tu rol o tu relación con este recurso no te habilita para verlo o modificarlo.
                Si crees que esto es un error, contacta al líder del proyecto o a un administrador.
            </p>

            <div class="mt-6 flex gap-3">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                    Volver
                </a>
                @auth
                    <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Ir a mis proyectos
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Iniciar sesión
                    </a>
                @endauth
            </div>
        </div>
    </body>
</html>
