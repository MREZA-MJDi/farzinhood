<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InventoryAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'in:restock,adjustment,return',
            ],

            'quantity' => [
                'required',
                'integer',
                'not_in:0',
                'between:-100000,100000',
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'quantity' => $this->integer('quantity'),
            'note' => $this->filled('note')
                ? trim($this->input('note'))
                : null,
        ]);
    }
}
