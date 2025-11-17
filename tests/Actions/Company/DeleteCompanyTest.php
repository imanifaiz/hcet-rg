<?php

use App\Actions\Company\CreateCompany;
use App\Actions\Company\DeleteCompany;
use App\Actions\Company\UpdateCompany;
use App\DTOs\CompanyDto;
use App\Models\Company;

it('can create delete company', function() {
    $data = CompanyDto::fromArray([
        'name' => 'Company',
        'email' => 'info@company.com',
        'website' => 'http://company.com',
    ]);

    $company = app(CreateCompany::class)->execute($data);

    app(DeleteCompany::class)->execute($company);

    expect(Company::count())->toEqual(0);
});
