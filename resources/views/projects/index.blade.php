<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">Proyectos</h2>
                <p class="text-sm text-gray-500 mt-1">Los equipos y tareas que gestionas o en los que colaboras.</p>
            </div>

            @can('create', App\Models\Project::class)
                <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-indigo-500 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Nuevo proyecto
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <form method="GET" class="flex items-end gap-3 bg-white p-4 rounded-xl border border-gray-200">
                <div>
                    <x-input-label for="status" value="Estado" class="text-xs uppercase tracking-wide text-gray-500" />
                    <select id="status" name="status" class="mt-1 block rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        @foreach (['activo', 'pausado', 'finalizado'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <x-secondary-button type="submit">Filtrar</x-secondary-button>
                @if (request('status'))
                    <a href="{{ route('projects.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Limpiar</a>
                @endif
            </form>

            @if ($projects->isEmpty())
                <div class="bg-white p-10 rounded-xl border border-dashed border-gray-300 text-center">
                    <p class="text-gray-500">No hay proyectos para mostrar todavía.</p>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="group block bg-white p-5 rounded-xl border border-gray-200 hover:border-indigo-300 hover:shadow-md transition">
                        <div class="flex justify-between items-start gap-2">
                            <h3 class="font-semibold text-gray-900 group-hover:text-indigo-700 transition">{{ $project->name }}</h3>
                            <x-badge type="project-status" :value="$project->status" />
                        </div>
                        <p class="text-sm text-gray-500 mt-2 line-clamp-2 min-h-[2.5rem]">{{ $project->description ?: 'Sin descripción.' }}</p>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                            <span class="text-xs text-gray-400">Dueño: {{ $project->owner?->name }}</span>
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $project->tasks_count }} tareas
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            {{ $projects->links() }}
        </div>
    </div>
</x-app-layout>
