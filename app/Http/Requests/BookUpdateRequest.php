<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
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
            "id" => "required|exists:books,id",
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'name' => 'required|max:30|string|min:3',
            'img' => 'mimes:jpg,png',
            'description' => 'required|string|min:30',
            'number_owned' => 'required|integer|min:1'
        ];
    }
}
