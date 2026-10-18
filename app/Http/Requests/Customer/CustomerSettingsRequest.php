<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() === true
            && $this->user()?->is_active === true;
    }

    public function rules(): array
    {
        $userId = $this->user()?->getKey();

        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name', '')),
            'email' => mb_strtolower(trim((string) $this->input('email', ''))),
        ]);
    }
}
