<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500"><a href="{{ route('projects.show', $project) }}" class="hover:underline">{{ $project->name }}</a></p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $task->title }}</h2>
            </div>
            @can('update', $task)
                <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" class="text-sm text-gray-600 hover:underline">Editar tarea</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 rounded-lg shadow space-y-4">
                @if ($task->description)
                    <p class="text-gray-700">{{ $task->description }}</p>
                @endif

                <dl class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-400">Estado</dt>
                        <dd class="font-medium">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Prioridad</dt>
                        <dd class="font-medium">{{ ucfirst($task->priority) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Responsable</dt>
                        <dd class="font-medium">{{ $task->assignee?->name ?? 'Sin asignar' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Vence</dt>
                        <dd class="font-medium">{{ $task->due_date?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                </dl>

                @can('update', $task)
                    <form method="POST" action="{{ route('tasks.status', $task) }}" class="flex items-center gap-2 pt-4 border-t">
                        @csrf
                        @method('PATCH')
                        <label for="status" class="text-sm text-gray-600">Cambiar estado:</label>
                        <select id="status" name="status" class="rounded-md border-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                            @foreach (['pendiente', 'en_progreso', 'completada'] as $status)
                                <option value="{{ $status }}" @selected($task->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                    </form>
                @endcan

                @can('assign', $task)
                    <form method="POST" action="{{ route('tasks.assign', $task) }}" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <label for="assignee_id" class="text-sm text-gray-600">Reasignar a:</label>
                        <select id="assignee_id" name="assignee_id" class="rounded-md border-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                            @foreach ($assignableUsers as $user)
                                <option value="{{ $user->id }}" @selected($task->assignee_id === $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </form>
                @endcan
            </div>

            <div class="bg-white p-6 rounded-lg shadow space-y-4">
                <h3 class="font-semibold text-gray-800">Comentarios</h3>

                <div class="space-y-3">
                    @forelse ($task->comments as $comment)
                        <div class="flex justify-between items-start bg-gray-50 rounded-md p-3">
                            <div>
                                <p class="text-sm text-gray-800">{{ $comment->body }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $comment->user->name }} · {{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            @can('delete', $comment)
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('¿Eliminar este comentario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar</button>
                                </form>
                            @endcan
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Todavía no hay comentarios.</p>
                    @endforelse
                </div>

                @can('create', [App\Models\Comment::class, $task])
                    <form method="POST" action="{{ route('tasks.comments.store', $task) }}" class="pt-4 border-t space-y-2">
                        @csrf
                        <textarea name="body" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="Escribe un comentario...">{{ old('body') }}</textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-1" />
                        <x-primary-button>Comentar</x-primary-button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
