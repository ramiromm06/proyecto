<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">Administración de usuarios</h2>
        <p class="text-sm text-gray-500 mt-1">Asigna el rol global de cada usuario en el sistema.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
                @foreach ($users as $user)
                    <div class="flex justify-between items-center gap-4 p-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            <x-badge type="role" :value="$user->roles->first()?->name ?? 'invitado'" />
                            <form method="POST" action="{{ route('admin.users.assignRole', $user) }}">
                                @csrf
                                <select name="role" class="rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                                    @foreach (['admin', 'lider', 'colaborador', 'invitado'] as $role)
                                        <option value="{{ $role }}" @selected($user->hasRole($role))>{{ ucfirst($role) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
