<?php

namespace App\Http\Requests;

use App\Models\Favorite;
use Illuminate\Foundation\Http\FormRequest;

class FavoriteDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $favoriteId = $this->route('id');
        $favorite = Favorite::find($favoriteId);
        if(!$favorite){
            return false;
        }
        if($favorite->user_id !== auth()->user()->id){
            return false;
        }
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'favorite_id' => $this->route('id')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "favorite_id" => "required|exists:favorites,id"
        ];
    }
}
