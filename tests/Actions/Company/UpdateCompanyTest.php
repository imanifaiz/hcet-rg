<?php

use App\Actions\Company\CreateCompany;
use App\Actions\Company\UpdateCompany;
use App\DTOs\CompanyDto;
use App\Models\Company;

it('can create update company', function() {
    $data = CompanyDto::fromArray([
        'name' => 'Company',
        'email' => 'info@company.com',
        'website' => 'http://company.com',
    ]);

    $company = app(CreateCompany::class)->execute($data);

    $updated = CompanyDto::fromArray([
        'name' => 'Company Edited',
        'email' => 'info.edited@company.com',
    ]);

    $company = app(UpdateCompany::class)->execute($company, $updated);

    expect($company)->wasChanged(['name', 'email'])
        ->and(Company::count())->toEqual(1)
        ->and($company->name)->toEqual('Company Edited')
        ->and($company->email)->toEqual('info.edited@company.com');
});
