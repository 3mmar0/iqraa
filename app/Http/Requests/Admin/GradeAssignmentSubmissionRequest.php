<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GradeAssignmentSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'return_to' => ['nullable', 'string'],
            'return_course_id' => ['nullable', 'integer'],
            'return_tab' => ['nullable', 'string'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'required' => 'هذا الحقل مطلوب.',
            'score.min' => 'الدرجة يجب أن تكون بين 0 و 100.',
            'score.max' => 'الدرجة يجب أن تكون بين 0 و 100.',
            'score.numeric' => 'الدرجة يجب أن تكون رقماً.',
        ];
    }
}
