<?php

namespace App\Actions\Company;

use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteCompany
{
    public function execute(Company $company): void
    {
        DB::transaction(function() use ($company) {
            if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }

            $company->delete();
        });
    }
}
