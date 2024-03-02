<?php

namespace App\Http\Requests;

use App\Models\Book;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRestoreRequest extends FormRequest
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
            "id" => [
                        "required",
                        Rule::exists('books')->where(function ($query){
                            $query->whereNotNull('deleted_at');
                        }),
                        function(string $attribute, mixed $value, Closure $fail){
                            $book = Book::withTrashed()->find($value);
        
                            if($book->category->trashed()){
                                $fail("Cannot restore book with inactive category");
                            }

                            if($book->author->trashed()){
                                $fail("Cannot restore book with inactive author");
                            }
                        }
                    ]
        ];
    }
}
