<?php

use App\Models\Company;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

dataset('company_data', [
    fn () => [
        'name' => 'Company',
        'email' => 'info@company.com',
        'website' => 'http://company.com',
    ]
]);

dataset('company_update_data', [
    fn () => [
        'name' => 'new name',
        'email' => 'info.edited@company.com',
    ]
]);

beforeEach(function () {
    if (!isset($this->adminUser)) {
        $this->adminUser = User::factory()->admin()->create();
    }

    if (!isset($this->normalUser)) {
        $this->normalUser = User::factory()->normalUser()->create();
    }
});

it('can view index page', function () {
    actingAs($this->adminUser);

    $response = get(route('admin.companies.index'));

    expect($response)->assertStatus(200);
});

it('can create company', function (array $companyData) {
    actingAs($this->adminUser);

    $response = post(route('admin.companies.store'), $companyData);

    expect(Company::count())->toEqual(1)
        ->and($response)->assertRedirect(route('admin.companies.index'));
})->with('company_data');

it('can update company', function (array $companyData, array $companyUpdateData) {
    actingAs($this->adminUser);

    post(route('admin.companies.store'), $companyData);

    $company = Company::first();

    put(route('admin.companies.update', $company), $companyUpdateData);

    $company->refresh();

    expect($company)->wasChanged(['name', 'email'])
        ->and(Company::count())->toEqual(1)
        ->and($company->name)->toEqual($companyUpdateData['name'])
        ->and($company->email)->toEqual($companyUpdateData['email']);
})->with('company_data', 'company_update_data');

it('can delete company', function (array $companyData) {
    actingAs($this->adminUser);

    post(route('admin.companies.store'), $companyData);

    $company = Company::first();

    $response = delete(route('admin.companies.destroy', $company));

    expect($response)->assertStatus(302)
        ->and(Company::count())->toEqual(0);
})->with('company_data');

// Negative test
it('restrict normal user from viewing company', function () {
    actingAs($this->normalUser);

    $response = get(route('admin.companies.index'));

    $response->assertForbidden();
});

it('restrict normal user from creating new company', function (array $companyData) {
    actingAs($this->normalUser);

    $response = post(route('admin.companies.store'), $companyData);

    $response->assertForbidden();
})->with('company_data');

it('restrict normal user from updating a company', function (array $companyData, array $companyUpdateData) {
    actingAs($this->adminUser);

    post(route('admin.companies.store'), $companyData);

    $company = Company::first();

    actingAs($this->normalUser);

    $response = put(route('admin.companies.update', $company), $companyUpdateData);

    $response->assertForbidden();
})->with('company_data', 'company_update_data');

it('restrict normal user from deleting a company', function (array $companyData) {
    actingAs($this->adminUser);

    post(route('admin.companies.store'), $companyData);

    $company = Company::first();

    actingAs($this->normalUser);

    $response = delete(route('admin.companies.destroy', $company));

    $response->assertForbidden();
})->with('company_data');
