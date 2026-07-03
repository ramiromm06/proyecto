<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $projects = Project::query()
            ->when(! $user->hasRole('admin'), function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhereHas('members', fn ($q) => $q->where('user_id', $user->id));
            })
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->withCount('tasks')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);

        return view('projects.create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = Project::create($request->validated() + ['owner_id' => auth()->id()]);

        return redirect()->route('projects.show', $project)->with('success', 'Proyecto creado correctamente.');
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        $tasks = $project->tasks()
            ->with(['assignee', 'labels'])
            ->when(request('task_status'), fn ($q, $status) => $q->where('status', $status))
            ->when(request('priority'), fn ($q, $priority) => $q->where('priority', $priority))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $project->load('members', 'owner');

        $availableUsers = auth()->user()->can('manageMembers', $project)
            ? User::whereNotIn('id', $project->members->pluck('id')->push($project->owner_id))->orderBy('name')->get()
            : collect();

        return view('projects.show', compact('project', 'tasks', 'availableUsers'));
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return redirect()->route('projects.show', $project)->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Proyecto eliminado correctamente.');
    }
}
