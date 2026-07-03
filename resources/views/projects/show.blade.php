<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-start gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight">{{ $project->name }}</h2>
                    <x-badge type="project-status" :value="$project->status" />
                </div>
                <p class="text-sm text-gray-500 mt-1">Dueño: {{ $project->owner->name }}</p>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shrink-0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                    Editar proyecto
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($project->description)
                <div class="bg-white p-5 rounded-xl border border-gray-200 text-gray-600 text-sm leading-relaxed">
                    {{ $project->description }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-gray-900">Tareas</h3>
                        @can('create', [App\Models\Task::class, $project])
                            <a href="{{ route('projects.tasks.create', $project) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 rounded-lg text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                Nueva tarea
                            </a>
                        @endcan
                    </div>

                    <form method="GET" class="flex flex-wrap items-end gap-3 bg-white p-4 rounded-xl border border-gray-200">
                        <div>
                            <x-input-label for="task_status" value="Estado" class="text-xs uppercase tracking-wide text-gray-500" />
                            <select id="task_status" name="task_status" class="mt-1 block rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                @foreach (['pendiente', 'en_progreso', 'completada'] as $status)
                                    <option value="{{ $status }}" @selected(request('task_status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="priority" value="Prioridad" class="text-xs uppercase tracking-wide text-gray-500" />
                            <select id="priority" name="priority" class="mt-1 block rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todas</option>
                                @foreach (['baja', 'media', 'alta'] as $priority)
                                    <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ ucfirst($priority) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-secondary-button type="submit">Filtrar</x-secondary-button>
                        @if (request('task_status') || request('priority'))
                            <a href="{{ route('projects.show', $project) }}" class="text-sm text-gray-500 hover:text-gray-700 pb-2.5">Limpiar</a>
                        @endif
                    </form>

                    <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100 overflow-hidden">
                        @forelse ($tasks as $task)
                            <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="flex justify-between items-center gap-4 p-4 hover:bg-gray-50 transition">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 truncate">{{ $task->title }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $task->assignee?->name ?? 'Sin asignar' }}
                                        @if ($task->due_date)
                                            · Vence {{ $task->due_date->format('d/m/Y') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <x-badge type="priority" :value="$task->priority" />
                                    <x-badge type="task-status" :value="$task->status" />
                                </div>
                            </a>
                        @empty
                            <p class="p-6 text-sm text-gray-500 text-center">No hay tareas que coincidan con el filtro.</p>
                        @endforelse
                    </div>

                    {{ $tasks->links() }}
                </div>

                <div class="space-y-4">
                    <div class="bg-white p-5 rounded-xl border border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-3">Miembros</h3>
                        <ul class="space-y-3">
                            @foreach ($project->members as $member)
                                <li class="flex justify-between items-center gap-2">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </span>
                                        <span class="text-sm text-gray-700 truncate">{{ $member->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <x-badge type="role" :value="$member->pivot->project_role" />
                                        @can('manageMembers', $project)
                                            <form method="POST" action="{{ route('projects.members.destroy', [$project, $member]) }}" onsubmit="return confirm('¿Quitar a {{ $member->name }} del proyecto?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Quitar">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        @can('manageMembers', $project)
                            <form method="POST" action="{{ route('projects.members.store', $project) }}" class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                                @csrf
                                <select name="user_id" class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Selecciona un usuario</option>
                                    @foreach ($availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                <select name="project_role" class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="colaborador">Colaborador</option>
                                    <option value="lider">Líder</option>
                                    <option value="invitado">Invitado</option>
                                </select>
                                <x-secondary-button type="submit" class="w-full justify-center">Agregar miembro</x-secondary-button>
                                <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
