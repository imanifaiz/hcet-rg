<?php

namespace App\Http\Controllers;

use App\Actions\Company\CreateCompany;
use App\Actions\Company\DeleteCompany;
use App\Actions\Company\UpdateCompany;
use App\DTOs\CompanyDto;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Company::class);
    }

    public function index(): InertiaResponse
    {
        return Inertia::render('Admin/Companies/Index', []);
    }

    public function store(StoreCompanyRequest $request, CreateCompany $creator): RedirectResponse
    {
        $creator->execute(CompanyDto::fromRequest($request));

        return redirect()->route('admin.companies.index')->with('flash', [
            'bannerStyle' => 'success',
            'banner' => 'Company successfully created.'
        ]);
    }

    public function update(UpdateCompanyRequest $request, Company $company, UpdateCompany $updater): RedirectResponse
    {
        $updater->execute($company, CompanyDto::fromRequest($request));

        return redirect()->route('admin.companies.index')->with('flash', [
            'bannerStyle' => 'success',
            'banner' => 'Company successfully updated.'
        ]);
    }

    public function destroy(Company $company, DeleteCompany $deleter): RedirectResponse
    {
        if ($company->employees()->exists()) {
            return redirect()->back()
                ->with('flash', [
                    'bannerStyle' => 'error',
                    'banner' => 'Cannot delete company. This company has employees associated with it. Please remove or transfer all employees before deleting.'
                ]);
        }

        $deleter->execute($company);

        return redirect()->back()
            ->with('flash', [
            'bannerStyle' => 'success',
            'banner' => 'Company successfully deleted.'
        ]);
    }
}
