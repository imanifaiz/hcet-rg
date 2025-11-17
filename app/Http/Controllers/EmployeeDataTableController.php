<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeDataTableController extends Controller
{
    public function __invoke(Request $request)
    {
        $sortBy = $request->input('sortField', 'created_at');
        $sortOrder = $request->input('sortOrder', 'descend') === 'ascend' ? 'asc' : 'desc';

        $allowedSortColumns = ['created_at', 'first_name', 'last_name'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        return Employee::query()
            ->with('company')
            ->when($request->globalSearch, fn ($q) => $q->where(
                fn ($q) => $q->whereRaw('LOWER(first_name) like ?', ['%' . strtolower($request->globalSearch) . '%'])
                    ->orWhereRaw('LOWER(last_name) like ?', ['%' . strtolower($request->globalSearch) . '%'])
                    ->orWhereRaw('LOWER(email) like ?', ['%' . strtolower($request->globalSearch) . '%'])
                    ->orWhereRaw('phone_no like ?', ['%' . $request->globalSearch . '%'])
            ))
            ->orderBy($sortBy, $sortOrder)
            ->paginate($request->pageSize)
            ->through(fn (Employee $company) => EmployeeResource::make($company));
    }
}
