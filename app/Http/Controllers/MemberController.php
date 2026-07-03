<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function store(StoreMemberRequest $request, Project $project): RedirectResponse
    {
        $project->members()->attach($request->input('user_id'), [
            'project_role' => $request->input('project_role'),
        ]);

        return back()->with('success', 'Miembro agregado al proyecto.');
    }

    public function update(Request $request, Project $project, User $user): RedirectResponse
    {
        $this->authorize('manageMembers', $project);

        $request->validate([
            'project_role' => ['required', 'in:lider,colaborador,invitado'],
        ]);

        $project->members()->updateExistingPivot($user->id, [
            'project_role' => $request->input('project_role'),
        ]);

        return back()->with('success', 'Rol del miembro actualizado.');
    }

    public function destroy(Project $project, User $user): RedirectResponse
    {
        $this->authorize('manageMembers', $project);

        $project->members()->detach($user->id);

        return back()->with('success', 'Miembro removido del proyecto.');
    }
}
