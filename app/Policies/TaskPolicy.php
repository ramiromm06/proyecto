<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Cualquier miembro del proyecto (o admin) puede ver sus tareas.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->hasRole('admin') || $task->project->isMember($user);
    }

    /**
     * Solo miembros del proyecto con permiso "crear tarea" pueden crear tareas en él.
     */
    public function create(User $user, Project $project): bool
    {
        return $user->hasRole('admin')
            || ($user->can('crear tarea') && $project->isMember($user));
    }

    /**
     * El dueño del proyecto (lider) puede editar cualquier tarea de su proyecto;
     * un colaborador solo puede editar las tareas que tiene asignadas.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->hasRole('admin')
            || ($user->can('editar tarea') && (
                $task->project->owner_id === $user->id
                || $task->assignee_id === $user->id
            ));
    }

    /**
     * Solo el dueño del proyecto (lider) puede eliminar tareas de su proyecto.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->hasRole('admin')
            || ($user->can('eliminar tarea') && $task->project->owner_id === $user->id);
    }

    /**
     * Reasignar una tarea a otro miembro: solo el dueño del proyecto.
     */
    public function assign(User $user, Task $task): bool
    {
        return $user->hasRole('admin')
            || ($user->can('asignar tarea') && $task->project->owner_id === $user->id);
    }

    public function restore(User $user, Task $task): bool
    {
        return $user->hasRole('admin') || $task->project->owner_id === $user->id;
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $user->hasRole('admin');
    }
}
