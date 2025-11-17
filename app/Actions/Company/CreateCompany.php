<?php

namespace App\Actions\Company;

use App\DTOs\CompanyDto;
use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreateCompany
{
    public function execute(CompanyDto $data): Company
    {
        return DB::transaction(function() use ($data) {
            if ($data->logo instanceof UploadedFile) {
                $logo = $data->logo->store('logos', [
                    'disk' => 'public'
                ]);
            }

            return Company::create(
                array_merge($data->toArray(), ['logo' => $logo ?? null])
            );
        });
    }
}
