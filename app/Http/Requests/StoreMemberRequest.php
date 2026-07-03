<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manageMembers', $this->route('project'));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('project_user', 'user_id')->where('project_id', $this->route('project')->id),
            ],
            'project_role' => ['required', 'in:lider,colaborador,invitado'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.unique' => 'Ese usuario ya es miembro del proyecto.',
        ];
    }
}
