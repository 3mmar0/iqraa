<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $type = (string) $this->input('type');
        if ($type === 'short_text') {
            $this->merge(['type' => 'text']);
        }

        $options = $this->input('options', []);
        if (! is_array($options)) {
            $options = [];
        }

        $normalized = [];
        foreach ($options as $option) {
            if (! is_array($option)) {
                continue;
            }
            $normalized[] = [
                'body' => trim((string) ($option['body'] ?? '')),
                'is_correct' => filter_var($option['is_correct'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ];
        }

        $this->merge([
            'options' => $normalized,
            'points' => $this->input('points', 1),
        ]);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(['single', 'multiple', 'text'])],
            'body' => ['required', 'string'],
            'points' => ['required', 'integer', 'min:1'],
            'options' => ['nullable', 'array'],
            'options.*.body' => ['nullable', 'string', 'max:500'],
            'options.*.is_correct' => ['nullable', 'boolean'],
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
            'type.in' => 'نوع السؤال غير صالح.',
            'points.min' => 'يجب أن تكون الدرجة 1 على الأقل.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $type = (string) $this->input('type');
            if ($type === 'text') {
                return;
            }

            $options = collect($this->input('options', []))
                ->filter(fn ($o) => is_array($o) && trim((string) ($o['body'] ?? '')) !== '');

            if ($options->count() < 2) {
                $validator->errors()->add('options', 'يجب إضافة خيارين على الأقل لأسئلة الاختيار.');

                return;
            }

            $correctCount = $options->filter(fn ($o) => ! empty($o['is_correct']))->count();

            if ($type === 'single' && $correctCount !== 1) {
                $validator->errors()->add('options', 'يجب تحديد إجابة صحيحة واحدة لسؤال الاختيار الواحد.');
            }

            if ($type === 'multiple' && $correctCount < 1) {
                $validator->errors()->add('options', 'يجب تحديد إجابة صحيحة واحدة على الأقل.');
            }
        });
    }
}
