<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() === true;
    }

    public function rules(): array
    {
        return [
            'address_id' => [
                'required',
                'integer',
                'exists:addresses,id',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'payment_method' => [
                'required',
                'string',
                'in:gateway',
            ],
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'address_id' => $this->integer('address_id'),
            'notes' => $this->filled('notes')
                ? trim($this->input('notes'))
                : null,
        ]);
    }
}
