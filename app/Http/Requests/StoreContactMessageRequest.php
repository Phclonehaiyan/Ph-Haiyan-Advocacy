<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'organization' => ['nullable', 'string', 'max:120'],
            'inquiry_type' => ['required', Rule::in(array_keys(config('site.inquiry_types')))],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:60', 'max:4000'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->has('message')) {
                    return;
                }

                $message = trim((string) $this->input('message', ''));

                if ($message === '') {
                    return;
                }

                preg_match_all('/\S+/u', $message, $matches);
                $wordCount = count($matches[0]);

                if ($wordCount < 8) {
                    $validator->errors()->add(
                        'message',
                        'Please add at least 8 words so the team has enough context to respond well.'
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'message.min' => 'Please add more detail so the team has enough context to respond well.',
            'inquiry_type.required' => 'Please choose the inquiry type that best fits your message.',
        ];
    }
}
