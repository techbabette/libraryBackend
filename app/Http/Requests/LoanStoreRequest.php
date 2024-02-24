<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Models\Loan;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class LoanStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //Check if user already has book loaned out
        $userId = auth()->user()->id;
        $bookId = $this->input('book_id');

        $alreadyLoanedOut = Loan::where('user_id', '=', $userId)->where('book_id', '=', $bookId)->exists();
        return !$alreadyLoanedOut;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "book_id" => [
                "required",
                function (string $attribute, mixed $value, Closure $fail){
                    $book = Book::find($value);
                    if(!$book){
                        $fail("Book not active");
                        return;
                    }
                    if($book->currentlyAvailable() < 1){
                        $fail("Zero copies available");
                    }
                }
            ]
        ];
    }
}
