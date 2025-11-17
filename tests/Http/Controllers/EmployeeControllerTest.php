<?php

use App\Models\Employee;
use App\Models\User;
use App\Notifications\NewEmployeeAdded;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    if (!isset($this->company)) {
        $this->company = \App\Models\Company::factory()->create();
    }

    if (!isset($this->adminUser)) {
        $this->adminUser = User::factory()->admin()->create();
    }

    if (!isset($this->normalUser)) {
        $this->normalUser = User::factory()->normalUser()->create();
    }
});

dataset('employee_data', [
    function() {
        $faker = app(Faker\Generator::class);

        return [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->email,
            'phone_no' => $faker->phoneNumber,
            'company_id' => $this->company->id
        ];
    }
]);

dataset('employee_update_data', [
    fn () => [
        'first_name' => 'first name',
        'last_name' => 'last name',
        'email' => 'info.edited@company.com',
    ]
]);

it('can view index page', function () {
    actingAs($this->adminUser);

    $response = get(route('admin.employees.index'));

    expect($response)->assertStatus(200);
});

it('can create employee', function (array $employeeData) {
    actingAs($this->adminUser);

    Notification::fake();
    $response = post(route('admin.employees.store'), $employeeData);

    expect(Employee::count())->toEqual(1)
        ->and($response)->assertRedirect(route('admin.employees.index'));

    Notification::assertSentTo($this->company, NewEmployeeAdded::class);
})->with('employee_data');

it('can update employee', function (array $employeeData, array $employeeUpdateData) {
    actingAs($this->adminUser);

    post(route('admin.employees.store'), $employeeData);

    $employee = Employee::first();

    put(route('admin.employees.update', $employee), $employeeUpdateData);

    $employee->refresh();

    expect($employee)->wasChanged(['first_name', 'last_name', 'email'])
        ->and(Employee::count())->toEqual(1)
        ->and($employee->first_name)->toEqual($employeeUpdateData['first_name'])
        ->and($employee->last_name)->toEqual($employeeUpdateData['last_name'])
        ->and($employee->email)->toEqual($employeeUpdateData['email']);
})->with('employee_data', 'employee_update_data');

it('can delete employee', function (array $employeeData) {
    actingAs($this->adminUser);

    post(route('admin.employees.store'), $employeeData);

    $employee = Employee::first();

    $response = delete(route('admin.employees.destroy', $employee));

    expect($response)->assertStatus(302)
        ->and(Employee::count())->toEqual(0);
})->with('employee_data');

// Negative test
it('restrict normal user from viewing employee', function () {
    actingAs($this->normalUser);

    $response = get(route('admin.employees.index'));

    $response->assertForbidden();
});

it('restrict normal user from creating new employee', function (array $employeeData) {
    actingAs($this->normalUser);

    $response = post(route('admin.employees.store'), $employeeData);

    $response->assertForbidden();
})->with('employee_data');

it('restrict normal user from updating a employee', function (array $employeeData, array $employeeUpdateData) {
    actingAs($this->adminUser);

    post(route('admin.employees.store'), $employeeData);

    $employee = Employee::first();

    actingAs($this->normalUser);

    $response = put(route('admin.employees.update', $employee), $employeeUpdateData);

    $response->assertForbidden();
})->with('employee_data', 'employee_update_data');

it('restrict normal user from deleting a employee', function (array $employeeData) {
    actingAs($this->adminUser);

    post(route('admin.employees.store'), $employeeData);

    $employee = Employee::first();

    actingAs($this->normalUser);

    $response = delete(route('admin.employees.destroy', $employee));

    $response->assertForbidden();
})->with('employee_data');
