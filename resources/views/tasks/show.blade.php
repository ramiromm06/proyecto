<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-start gap-4">
            <div>
                <p class="text-sm text-gray-500"><a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-600">{{ $project->name }}</a></p>
                <div class="flex items-center gap-3 mt-1">
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight">{{ $task->title }}</h2>
                    <x-badge type="task-status" :value="$task->status" />
                </div>
            </div>
            @can('update', $task)
                <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shrink-0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                    Editar tarea
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 rounded-xl border border-gray-200 space-y-5">
                @if ($task->description)
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $task->description }}</p>
                @endif

                <dl class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-400">Prioridad</dt>
                        <dd class="mt-1"><x-badge type="priority" :value="$task->priority" /></dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-400">Responsable</dt>
                        <dd class="mt-1 font-medium text-sm text-gray-800">{{ $task->assignee?->name ?? 'Sin asignar' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-400">Vence</dt>
                        <dd class="mt-1 font-medium text-sm text-gray-800">{{ $task->due_date?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-400">Creada</dt>
                        <dd class="mt-1 font-medium text-sm text-gray-800">{{ $task->created_at->format('d/m/Y') }}</dd>
                    </div>
                </dl>

                @if (auth()->user()->can('update', $task) || auth()->user()->can('assign', $task))
                    <div class="flex flex-wrap items-center gap-6 pt-4 border-t border-gray-100">
                        @can('update', $task)
                            <form method="POST" action="{{ route('tasks.status', $task) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <label for="status" class="text-sm text-gray-500">Cambiar estado:</label>
                                <select id="status" name="status" class="rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
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
                                <label for="assignee_id" class="text-sm text-gray-500">Reasignar a:</label>
                                <select id="assignee_id" name="assignee_id" class="rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                                    @foreach ($assignableUsers as $user)
                                        <option value="{{ $user->id }}" @selected($task->assignee_id === $user->id)>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        @endcan
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-200 space-y-4">
                <h3 class="font-semibold text-gray-900">Comentarios <span class="text-gray-400 font-normal">({{ $task->comments->count() }})</span></h3>

                <div class="space-y-3">
                    @forelse ($task->comments as $comment)
                        <div class="flex gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </span>
                            <div class="flex-1 bg-gray-50 rounded-lg p-3">
                                <div class="flex justify-between items-start gap-2">
                                    <p class="text-xs font-medium text-gray-700">{{ $comment->user->name }} <span class="font-normal text-gray-400">· {{ $comment->created_at->diffForHumans() }}</span></p>
                                    @can('delete', $comment)
                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('¿Eliminar este comentario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition text-xs">Eliminar</button>
                                        </form>
                                    @endcan
                                </div>
                                <p class="text-sm text-gray-700 mt-1">{{ $comment->body }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Todavía no hay comentarios.</p>
                    @endforelse
                </div>

                @can('create', [App\Models\Comment::class, $task])
                    <form method="POST" action="{{ route('tasks.comments.store', $task) }}" class="pt-4 border-t border-gray-100 space-y-2">
                        @csrf
                        <textarea name="body" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Escribe un comentario...">{{ old('body') }}</textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-1" />
                        <x-primary-button>Comentar</x-primary-button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
