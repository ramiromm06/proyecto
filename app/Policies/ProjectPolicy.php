<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Cualquier usuario autenticado puede ver el listado (filtrado luego por membresía).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Solo el admin, el dueño o los miembros del proyecto pueden verlo.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->hasRole('admin') || $project->isMember($user);
    }

    /**
     * Permiso por rol: quien tenga "crear proyecto" puede crear.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->can('crear proyecto');
    }

    /**
     * Permiso por rol + autorización por pertenencia: solo el dueño puede editar SU proyecto.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->hasRole('admin')
            || ($user->can('editar proyecto') && $project->owner_id === $user->id);
    }

    /**
     * Igual que update: permiso + pertenencia.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->hasRole('admin')
            || ($user->can('eliminar proyecto') && $project->owner_id === $user->id);
    }

    /**
     * Agregar/quitar miembros y cambiar su rol en el proyecto.
     */
    public function manageMembers(User $user, Project $project): bool
    {
        return $user->hasRole('admin')
            || ($user->can('gestionar miembros') && $project->owner_id === $user->id);
    }

    public function restore(User $user, Project $project): bool
    {
        return $user->hasRole('admin') || $project->owner_id === $user->id;
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return $user->hasRole('admin');
    }
}
