<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShopIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],

            'category' => [
                'nullable',
                'string',
                'max:255',
                'exists:categories,slug',
            ],

            'sort' => [
                'nullable',
                Rule::in([
                    'latest',
                    'price_asc',
                    'price_desc',
                    'popular',
                    'rating',
                ]),
            ],

            'min_price' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'max_price' => [
                'nullable',
                'integer',
                'min:0',
                'gte:min_price',
            ],

            'per_page' => [
                'nullable',
                'integer',
                'min:12',
                'max:48',
            ],
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'search' => $this->filled('search')
                ? trim($this->input('search'))
                : null,

            'min_price' => $this->filled('min_price')
                ? $this->integer('min_price')
                : null,

            'max_price' => $this->filled('max_price')
                ? $this->integer('max_price')
                : null,

            'per_page' => $this->filled('per_page')
                ? $this->integer('per_page')
                : 12,
        ]);
    }
}
