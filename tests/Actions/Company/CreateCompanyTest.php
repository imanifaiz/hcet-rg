<?php

use App\Actions\Company\CreateCompany;
use App\DTOs\CompanyDto;
use App\Models\Company;

it('can create new company', function() {
    $data = CompanyDto::fromArray([
        'name' => 'Company',
        'email' => 'info@company.com',
        'website' => 'http://company.com',
    ]);

    $company = app(CreateCompany::class)->execute($data);

    expect($company->wasRecentlyCreated)->toBeTrue()
        ->and(Company::count())->toEqual(1);
});
