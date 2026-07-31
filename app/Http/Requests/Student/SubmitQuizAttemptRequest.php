<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class SubmitQuizAttemptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'answers' => ['required', 'array'],
            'answers.*' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'answers.required' => 'أجب عن الأسئلة قبل الإرسال.',
        ];
    }
}
