<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'approved',
                    'rejected',
                ]),
            ],
        ];
    }
}
