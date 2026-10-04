<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() === true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'name' => trim($this->input('name')),
            'email' => strtolower(trim($this->input('email'))),
        ]);
    }
}
