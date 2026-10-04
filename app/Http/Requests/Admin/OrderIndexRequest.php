<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],

            'status' => [
                'nullable',
                'in:pending,processing,shipped,delivered,cancelled',
            ],

            'payment_status' => [
                'nullable',
                'in:pending,paid,failed,refunded',
            ],
        ];
    }
}
