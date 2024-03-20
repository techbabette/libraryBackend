<?php

namespace App\Http\Requests;

use App\Models\MessageType;
use Illuminate\Foundation\Http\FormRequest;
use Closure;

class MessageTypeDeleteRequest extends FormRequest
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
            "id" => 
                [
                    "required", 
                    "exists:message_types,id",
                    function ($attribute, mixed $value, Closure $fail){
                        $messageTypeToDelete = MessageType::find($value);
                        $messageTypeHasMessages = $messageTypeToDelete->messages->isNotEmpty();

                        if($messageTypeHasMessages){
                            $fail("Cannot delete message type if it has messages");
                        }
                    }
                ]
        ];
    }
}
