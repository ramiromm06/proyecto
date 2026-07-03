<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $project->name }}</h2>
                <p class="text-sm text-gray-500">Dueño: {{ $project->owner->name }} · Estado: {{ ucfirst($project->status) }}</p>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.edit', $project) }}" class="text-sm text-gray-600 hover:underline">Editar proyecto</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($project->description)
                <div class="bg-white p-5 rounded-lg shadow text-gray-700">
                    {{ $project->description }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">Tareas</h3>
                        @can('create', [App\Models\Task::class, $project])
                            <a href="{{ route('projects.tasks.create', $project) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-800 rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-gray-700">
                                Nueva tarea
                            </a>
                        @endcan
                    </div>

                    <form method="GET" class="flex flex-wrap items-end gap-3">
                        <div>
                            <x-input-label for="task_status" value="Estado" />
                            <select id="task_status" name="task_status" class="mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Todos</option>
                                @foreach (['pendiente', 'en_progreso', 'completada'] as $status)
                                    <option value="{{ $status }}" @selected(request('task_status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="priority" value="Prioridad" />
                            <select id="priority" name="priority" class="mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Todas</option>
                                @foreach (['baja', 'media', 'alta'] as $priority)
                                    <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ ucfirst($priority) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-secondary-button type="submit">Filtrar</x-secondary-button>
                    </form>

                    <div class="bg-white rounded-lg shadow divide-y">
                        @forelse ($tasks as $task)
                            <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="flex justify-between items-center p-4 hover:bg-gray-50">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $task->title }}</p>
                                    <p class="text-xs text-gray-500">
                                        Responsable: {{ $task->assignee?->name ?? 'Sin asignar' }}
                                        @if ($task->due_date)
                                            · Vence: {{ $task->due_date->format('d/m/Y') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">{{ ucfirst($task->priority) }}</span>
                                    <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                </div>
                            </a>
                        @empty
                            <p class="p-4 text-sm text-gray-500">No hay tareas que coincidan con el filtro.</p>
                        @endforelse
                    </div>

                    {{ $tasks->links() }}
                </div>

                <div class="space-y-4">
                    <div class="bg-white p-5 rounded-lg shadow">
                        <h3 class="font-semibold text-gray-800 mb-3">Miembros</h3>
                        <ul class="space-y-2">
                            @foreach ($project->members as $member)
                                <li class="flex justify-between items-center text-sm">
                                    <span>{{ $member->name }} <span class="text-xs text-gray-400">({{ $member->pivot->project_role }})</span></span>
                                    @can('manageMembers', $project)
                                        <form method="POST" action="{{ route('projects.members.destroy', [$project, $member]) }}" onsubmit="return confirm('¿Quitar a {{ $member->name }} del proyecto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:underline">Quitar</button>
                                        </form>
                                    @endcan
                                </li>
                            @endforeach
                        </ul>

                        @can('manageMembers', $project)
                            <form method="POST" action="{{ route('projects.members.store', $project) }}" class="mt-4 pt-4 border-t space-y-2">
                                @csrf
                                <select name="user_id" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                                    <option value="">Selecciona un usuario</option>
                                    @foreach ($availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                <select name="project_role" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
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
