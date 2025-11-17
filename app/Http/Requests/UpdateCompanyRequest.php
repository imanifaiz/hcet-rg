<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('companies', 'name')->ignore($this->route('company')->id),
            ],
            'email' => [
                'nullable',
                'email',
            ],
            'logo' => [
                'nullable',
                'mimetypes:image/jpeg,image/jpg,image/png',
                'max:2048',
            ],
            'website' => [
                'nullable',
                'url',
            ]
        ];
    }
}
