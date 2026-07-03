<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;

class CommentPolicy
{
    /**
     * Cualquier miembro del proyecto de la tarea (o admin) puede comentar.
     */
    public function create(User $user, Task $task): bool
    {
        return $user->hasRole('admin')
            || ($user->can('comentar') && $task->project->isMember($user));
    }

    /**
     * Solo el autor del comentario, el dueño del proyecto o el admin pueden eliminarlo.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->hasRole('admin')
            || $comment->user_id === $user->id
            || $comment->task->project->owner_id === $user->id;
    }
}
