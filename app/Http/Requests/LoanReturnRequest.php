<?php

namespace App\Http\Requests;

use App\Models\Loan;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class LoanReturnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $loanId = $this->route('id');
        $loan = Loan::find($loanId);
        if(!$loan){
            return false;
        }
        if(!Gate::allows('update-loan', $loan)){
            return false;
        }
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
            'loanId' => $this->route('id')
        ]);
    }

    public function rules(): array
    {
        return [
            "loanId" => [
                "required",
                "integer",
            ]
        ];
    }
}
