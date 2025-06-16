<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('POST')) {
            // store
            return [
                'key' => 'required|string|max:255',
                'value' => 'required|string',
                'language_id' => 'required|exists:languages,id',
                'tags' => 'nullable|array',
                'tags.*' => 'string',
            ];
        }

        // update
        return [
            'key' => 'sometimes|string|max:255',
            'value' => 'sometimes|string',
            'language_id' => 'sometimes|exists:languages,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
        ];
    }
}
