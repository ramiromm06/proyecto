<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', [Task::class, $this->route('project')]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:baja,media,alta'],
            'due_date' => ['nullable', 'date'],
            'assignee_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $project = $this->route('project');
            $assigneeId = (int) $this->input('assignee_id');

            if ($assigneeId && ! $project->members()->where('user_id', $assigneeId)->exists()
                && $project->owner_id !== $assigneeId) {
                $validator->errors()->add('assignee_id', 'El responsable debe ser miembro del proyecto.');
            }

            if (! $this->user()->hasRole(['admin', 'lider']) && $assigneeId !== $this->user()->id) {
                $validator->errors()->add('assignee_id', 'Solo puedes crear tareas asignadas a ti mismo.');
            }
        });
    }
}
