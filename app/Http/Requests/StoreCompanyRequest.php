<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
                'unique:companies,name',
            ],
            'email' => [
                'nullable',
                'email',
            ],
            'logo' => [
                'nullable',
                'mimetypes:image/jpeg,image/png',
                'max:2048',
            ],
            'website' => [
                'nullable',
                'url',
            ]
        ];
    }
}
