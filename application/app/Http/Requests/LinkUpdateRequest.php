<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            "id" => "required|exists:links,id",
            "access_level_id" => "required|exists:access_levels,id",
            "link_position_id" => "required|exists:link_positions,id",
            "text" => "required|string|max:20",
            "to" => "required|string|max:50",
            "icon" => "nullable|string|max:50",
            "weight" => "required|integer|min:0|max:100",
        ];
    }
}
