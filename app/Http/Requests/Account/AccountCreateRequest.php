<?php

namespace App\Http\Requests\Account;

use App\Enums\AccountTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $accountTypeEnum = array_column(AccountTypeEnum::cases(), 'value');

        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'account_type' => ['required', Rule::in($accountTypeEnum)],
            'amount' => ['required', 'numeric', 'min:0'],
        ];
    }
}
