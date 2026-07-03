<x-app-layout>
    <x-slot name="header">
        <p class="text-sm text-gray-500"><a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="hover:text-indigo-600">{{ $task->title }}</a></p>
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">Editar tarea</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-200">
                <form method="POST" action="{{ route('projects.tasks.update', [$project, $task]) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="title" value="Título" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title', $task->title) }}" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Descripción" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $task->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="priority" value="Prioridad" />
                            <select id="priority" name="priority" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (['baja', 'media', 'alta'] as $priority)
                                    <option value="{{ $priority }}" @selected(old('priority', $task->priority) === $priority)>{{ ucfirst($priority) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="due_date" value="Fecha límite" />
                            <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}" />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button>Guardar cambios</x-primary-button>
                        <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
                    </div>
                </form>

                @can('delete', $task)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <form method="POST" action="{{ route('projects.tasks.destroy', [$project, $task]) }}" onsubmit="return confirm('¿Eliminar esta tarea?');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>Eliminar tarea</x-danger-button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
