<?php

namespace App\Http\Requests;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Closure;
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
            "id" => [
                "required",
                "exists:books,id",
                ],
            'category_id' => [
                "required", 
                "exists:categories,id",
                function(string $attribute, mixed $value, Closure $fail){
                    $book = Book::withTrashed()->find($this->input('id'));
                    $bookTrashed = $book->trashed();
                    //If book is inactive, stop check and allow assignement to any category
                    if($bookTrashed){
                        return;
                    }

                    //If book is not inactive, check category
                    $requestedCategory = Category::find($value);
                    if(!$requestedCategory){
                        $fail("Cannot set active book category to inactive category");
                    }
                }
            ],
            'author_id' => [
                "required",
                "exists:authors,id",
                function(string $attribute, mixed $value, Closure $fail){
                    $book = Book::withTrashed()->find($this->input('id'));
                    $bookTrashed = $book->trashed();
                    //If book is inactive, stop check and allow assignement to any author
                    if($bookTrashed){
                        return;
                    }

                    //If book is not inactive, check author
                    $requestedAuthor = Author::find($value);
                    if(!$requestedAuthor){
                        $fail("Cannot set active book author to inactive author");
                    }
                }
            ],
            'name' => 'required|max:30|string|min:3',
            'img' => 'mimes:jpg,png',
            'description' => 'required|string|min:30',
            'number_owned' => 'required|integer|min:1'
        ];
    }
}
