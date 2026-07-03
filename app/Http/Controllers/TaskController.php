<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function create(Project $project): View
    {
        $this->authorize('create', [Task::class, $project]);

        $assignableUsers = $project->members->push($project->owner)->unique('id')->sortBy('name');

        return view('tasks.create', compact('project', 'assignableUsers'));
    }

    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
        $task = $project->tasks()->create($request->validated());

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Tarea creada correctamente.');
    }

    public function show(Project $project, Task $task): View
    {
        $this->authorize('view', $task);

        $task->load(['assignee', 'labels', 'comments.user']);

        $assignableUsers = auth()->user()->can('assign', $task)
            ? $project->members->push($project->owner)->unique('id')->sortBy('name')
            : collect();

        return view('tasks.show', compact('project', 'task', 'assignableUsers'));
    }

    public function edit(Project $project, Task $task): View
    {
        $this->authorize('update', $task);

        return view('tasks.edit', compact('project', 'task'));
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Tarea actualizada correctamente.');
    }

    public function destroy(Project $project, Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('projects.show', $project)->with('success', 'Tarea eliminada correctamente.');
    }

    public function status(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => ['required', 'in:pendiente,en_progreso,completada'],
        ]);

        $task->update(['status' => $request->input('status')]);

        return back()->with('success', 'Estado de la tarea actualizado.');
    }

    public function assign(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('assign', $task);

        $request->validate([
            'assignee_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $task->update(['assignee_id' => $request->input('assignee_id')]);

        return back()->with('success', 'Tarea reasignada correctamente.');
    }
}
