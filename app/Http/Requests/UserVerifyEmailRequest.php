<?php

namespace App\Http\Requests;

use App\Models\EmailVerificationToken;
use Illuminate\Foundation\Http\FormRequest;

class UserVerifyEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $requestedUserId = $this->route("id");
        $token = $this->route("token");

        $tokenExistsForUser = EmailVerificationToken::where('user_id', '=', $requestedUserId)->where('token', '=', $token)->count() > 0;

        return $tokenExistsForUser;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
