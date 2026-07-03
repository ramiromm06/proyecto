<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proyectos</h2>

            @can('create', App\Models\Project::class)
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Nuevo proyecto
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <form method="GET" class="flex items-end gap-3">
                <div>
                    <x-input-label for="status" value="Estado" />
                    <select id="status" name="status" class="mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                        <option value="">Todos</option>
                        @foreach (['activo', 'pausado', 'finalizado'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <x-secondary-button type="submit">Filtrar</x-secondary-button>
            </form>

            @if ($projects->isEmpty())
                <div class="bg-white p-6 rounded-lg shadow text-gray-500">
                    No hay proyectos para mostrar todavía.
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="block bg-white p-5 rounded-lg shadow hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <h3 class="font-semibold text-gray-900">{{ $project->name }}</h3>
                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">{{ ucfirst($project->status) }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $project->description }}</p>
                        <p class="text-xs text-gray-400 mt-3">{{ $project->tasks_count }} tareas</p>
                    </a>
                @endforeach
            </div>

            {{ $projects->links() }}
        </div>
    </div>
</x-app-layout>
