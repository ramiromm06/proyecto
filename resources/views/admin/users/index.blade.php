<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Administración de usuarios</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow divide-y">
                @foreach ($users as $user)
                    <div class="flex justify-between items-center p-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>

                        <form method="POST" action="{{ route('admin.users.assignRole', $user) }}" class="flex items-center gap-2">
                            @csrf
                            <select name="role" class="rounded-md border-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                                @foreach (['admin', 'lider', 'colaborador', 'invitado'] as $role)
                                    <option value="{{ $role }}" @selected($user->hasRole($role))>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endforeach
            </div>

            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
