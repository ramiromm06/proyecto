@props(['type', 'value'])
@php
    $palettes = [
        'project-status' => [
            'activo' => 'bg-emerald-100 text-emerald-700 ring-emerald-600/20',
            'pausado' => 'bg-amber-100 text-amber-700 ring-amber-600/20',
            'finalizado' => 'bg-gray-100 text-gray-600 ring-gray-500/20',
        ],
        'task-status' => [
            'pendiente' => 'bg-gray-100 text-gray-600 ring-gray-500/20',
            'en_progreso' => 'bg-sky-100 text-sky-700 ring-sky-600/20',
            'completada' => 'bg-emerald-100 text-emerald-700 ring-emerald-600/20',
        ],
        'priority' => [
            'baja' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
            'media' => 'bg-amber-100 text-amber-700 ring-amber-600/20',
            'alta' => 'bg-red-100 text-red-700 ring-red-600/20',
        ],
        'role' => [
            'admin' => 'bg-violet-100 text-violet-700 ring-violet-600/20',
            'lider' => 'bg-indigo-100 text-indigo-700 ring-indigo-600/20',
            'colaborador' => 'bg-sky-100 text-sky-700 ring-sky-600/20',
            'invitado' => 'bg-gray-100 text-gray-600 ring-gray-500/20',
        ],
    ];

    $classes = $palettes[$type][$value] ?? 'bg-gray-100 text-gray-600 ring-gray-500/20';
    $label = ucfirst(str_replace('_', ' ', $value));
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset whitespace-nowrap {$classes}"]) }}>
    {{ $label }}
</span>
