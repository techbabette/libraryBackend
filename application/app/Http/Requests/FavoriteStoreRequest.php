<?php

namespace App\Http\Requests;

use App\Models\Favorite;
use Illuminate\Foundation\Http\FormRequest;

class FavoriteStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = auth()->user()->id;
        $bookId = $this->input('book_id');

        $alreadyfavorite = Favorite::where('user_id', '=', $userId)->where('book_id', '=', $bookId)->exists();
        return !$alreadyfavorite;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "book_id" => "required|exists:books,id"
        ];
    }
}
