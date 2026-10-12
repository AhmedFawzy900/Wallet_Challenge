<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserTransactionRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01', 'max:100000000'],
            'currency' => ['required', 'string', 'size:3'],
            'reciever_account_number' => ['required', 'string', 'max:255'],
            'reciever_bank_code' => ['required', 'string', 'max:255'],
            'beneficiary_name' => ['required', 'string', 'max:255'],
            'payment_type' => ['required', 'string', 'max:255'],
            'charge_details' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'array', 'max:255'],
        ];
    }
}
