<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'TaskGestor') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="min-h-screen flex flex-col">
            <header class="max-w-5xl w-full mx-auto px-6 py-6 flex items-center justify-between">
                <div class="flex items-center gap-2 text-indigo-600">
                    <x-application-logo class="h-8 w-8" />
                    <span class="text-lg font-bold text-gray-900 tracking-tight">GestorPro</span>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-lg text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                            Ir a mis proyectos
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-lg text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                            Registrarse
                        </a>
                    @endauth
                </div>
            </header>

            <main class="flex-1 flex items-center">
                <div class="max-w-5xl w-full mx-auto px-6 py-16 text-center">
                    <p class="text-sm font-semibold text-indigo-600 uppercase tracking-widest">Gestión de proyectos colaborativos</p>
                    <h1 class="mt-3 text-4xl sm:text-5xl font-bold text-gray-900 tracking-tight">
                        Organiza equipos, tareas<br class="hidden sm:block"> y comentarios en un solo lugar
                    </h1>
                    <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">
                        Crea proyectos, agrega miembros con roles específicos, da seguimiento a tareas por estado
                        y prioridad, y colabora mediante comentarios — con control de acceso claro para cada rol.
                    </p>

                    <div class="mt-8 flex items-center justify-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 rounded-lg text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                                Ir a mis proyectos
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 rounded-lg text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                                Iniciar sesión
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Crear cuenta
                            </a>
                        @endauth
                    </div>

                    <div class="mt-16 grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
                        <div class="bg-white p-5 rounded-xl border border-gray-200">
                            <p class="font-semibold text-gray-900">Proyectos y equipos</p>
                            <p class="mt-1 text-sm text-gray-500">Cada proyecto tiene un dueño y miembros con un rol específico: líder, colaborador o invitado.</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl border border-gray-200">
                            <p class="font-semibold text-gray-900">Tareas con estado y prioridad</p>
                            <p class="mt-1 text-sm text-gray-500">Filtra, prioriza, reasigna y da seguimiento al avance de cada tarea del proyecto.</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl border border-gray-200">
                            <p class="font-semibold text-gray-900">Control de acceso por rol</p>
                            <p class="mt-1 text-sm text-gray-500">Cada acción está protegida según el rol y la pertenencia del usuario al recurso.</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
