<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'site_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'site_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'default_meta_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'default_meta_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'instagram' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'telegram' => [
                'nullable',
                'url',
                'max:2048',
            ],
        ];
    }
}
