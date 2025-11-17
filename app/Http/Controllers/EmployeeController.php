<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Employee::class);
    }

    public function index(): InertiaResponse
    {
        return Inertia::render('Admin/Employees/Index', [
            'companiesOpts' => Company::getForSelectOptions(),
        ]);
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        /** @var Employee $employee */
        $employee = Employee::create($request->validated());
        $employee->notifyCompany();

        return redirect()->route('admin.employees.index')->with('flash', [
            'bannerStyle' => 'success',
            'banner' => 'Employee successfully created.'
        ]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        return redirect()->route('admin.employees.index')->with('flash', [
            'bannerStyle' => 'success',
            'banner' => 'Employee successfully updated.'
        ]);
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->back()
            ->with('flash', [
            'bannerStyle' => 'success',
            'banner' => 'Employee successfully deleted.'
        ]);
    }
}
