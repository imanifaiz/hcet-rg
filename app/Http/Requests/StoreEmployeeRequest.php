<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Propaganistas\LaravelPhone\Rules\Phone;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
            ],
            'last_name' => [
                'required',
            ],
            'email' => [
                'nullable',
                'email',
            ],
            'phone_no' => [
                'nullable',
                (new Phone)->country('MY')->lenient(),
            ],
            'company_id' => [
                'required',
                'exists:companies,id',
            ],
        ];
    }
}
