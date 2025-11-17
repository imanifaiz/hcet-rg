<?php

namespace App\Actions\Company;

use App\DTOs\CompanyDto;
use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateCompany
{
    public function execute(Company $company, CompanyDto $data): Company
    {
        return DB::transaction(function() use ($company, $data) {
            $oldLogo = $company->logo;
            $input = $this->prepareInput($data);

            $company->update($input);

            $this->cleanupOldLogo($company, $oldLogo);

            return $company->refresh();
        });
    }

    protected function prepareInput(CompanyDto $data): array
    {
        $input = $data->toArray();

        if ($data->logo instanceof UploadedFile) {
            $input['logo'] = $data->logo->store('logos', ['disk' => 'public']);
        } else {
            unset($input['logo']);
        }

        return $input;
    }

    protected function cleanupOldLogo(Company $company, ?string $oldLogo): void
    {
        if ($oldLogo && $company->wasChanged(['logo']) && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }
    }
}
