<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLinkRequest extends FormRequest
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
        return [
            "access_level_id" => "required|exists:access_levels,id",
            "link_position_id" => "required|exists:link_positions,id",
            "text" => "required",
            "to" => "required",
            "icon" => "",
            "weight" => "required",
        ];
    }
}
