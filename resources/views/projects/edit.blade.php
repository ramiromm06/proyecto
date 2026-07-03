<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar proyecto</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" value="Nombre" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $project->name) }}" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Descripción" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $project->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="status" value="Estado" />
                        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @foreach (['activo', 'pausado', 'finalizado'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $project->status) === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Guardar cambios</x-primary-button>
                        <a href="{{ route('projects.show', $project) }}" class="text-sm text-gray-500 hover:underline">Cancelar</a>
                    </div>
                </form>

                @can('delete', $project)
                    <form method="POST" action="{{ route('projects.destroy', $project) }}" class="mt-6 pt-6 border-t" onsubmit="return confirm('¿Eliminar este proyecto?');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>Eliminar proyecto</x-danger-button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
