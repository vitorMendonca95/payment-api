<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric', 'min:0'],
            'payer' => ['required', 'integer', 'min:0', 'exists:users,id'],
            'payee' => ['required', 'integer', 'min:0', 'exists:users,id'],
        ];
    }
}
