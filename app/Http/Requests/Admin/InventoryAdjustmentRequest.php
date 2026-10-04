<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                Rule::in(['restock', 'adjustment', 'return']),
            ],

            'quantity' => [
                'required',
                'integer',
                'between:-100000,100000',
                'not_in:0',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (
                        in_array($this->input('type'), ['restock', 'return'], true)
                        && (int) $value < 1
                    ) {
                        $fail('برای افزایش موجودی، مقدار باید مثبت باشد.');
                    }
                },
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
