<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() === true;
    }

    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'order_id' => [
                'nullable',
                'integer',
                'exists:orders,id',
            ],

            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'body' => [
                'required',
                'string',
                'min:5',
                'max:5000',
            ],
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'rating' => $this->integer('rating'),

            'title' => $this->filled('title')
                ? trim($this->input('title'))
                : null,

            'body' => trim($this->input('body')),
        ]);
    }
}
