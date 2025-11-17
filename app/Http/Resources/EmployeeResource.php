<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => implode(' ', [$this->first_name, $this->last_name]),
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'company_id' => $this->company_id,
            'company' => $this->whenLoaded('company', CompanyResource::make($this->company)),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
