<?php

namespace App\Http\Requests\User;

use App\Enums\DocumentTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $documentTypeEnum = array_column(DocumentTypeEnum::cases(), 'value');

        if ($this->request->get('document_type') === DocumentTypeEnum::Cpf->value) {
            $documentTypeValidation = DocumentTypeEnum::Cpf->value;
        }

        if ($this->request->get('document_type') === DocumentTypeEnum::Cnpj->value) {
            $documentTypeValidation = DocumentTypeEnum::Cnpj->value;
        }

        $documentTypeValidation = $documentTypeValidation ?? '';

        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'document' => ['required' , 'unique:users,document', $documentTypeValidation],
            'document_type' => Rule::in($documentTypeEnum),
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%^&*()-_=+<>?]/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'The password is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must contain at least one lowercase letter, one uppercase letter, one number, and one symbol.',
        ];
    }
}
