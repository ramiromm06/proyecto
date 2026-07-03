<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nueva tarea — {{ $project->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <form method="POST" action="{{ route('projects.tasks.store', $project) }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="title" value="Título" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title') }}" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Descripción" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="priority" value="Prioridad" />
                            <select id="priority" name="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @foreach (['baja', 'media', 'alta'] as $priority)
                                    <option value="{{ $priority }}" @selected(old('priority') === $priority)>{{ ucfirst($priority) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="due_date" value="Fecha límite" />
                            <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" value="{{ old('due_date') }}" />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="assignee_id" value="Responsable" />
                        <select id="assignee_id" name="assignee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="">Selecciona un responsable</option>
                            @foreach ($assignableUsers as $user)
                                <option value="{{ $user->id }}" @selected((string) old('assignee_id') === (string) $user->id || (! auth()->user()->hasRole(['admin', 'lider']) && auth()->id() === $user->id))>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('assignee_id')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Crear tarea</x-primary-button>
                        <a href="{{ route('projects.show', $project) }}" class="text-sm text-gray-500 hover:underline">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
