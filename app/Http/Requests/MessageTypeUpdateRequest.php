<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageTypeUpdateRequest extends FormRequest
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
    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id')
        ]);
    }

    public function rules(): array
    {
        return [
            "id" => "required|exists:message_types,id",
            "name" => "required|string|max:50|min:4|unique:message_types"
        ];
    }
}
