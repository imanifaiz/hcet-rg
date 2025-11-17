<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyDataTableController extends Controller
{
    public function __invoke(Request $request)
    {
        $sortBy = $request->input('sortField', 'created_at');
        $sortOrder = $request->input('sortOrder', 'descend') === 'ascend' ? 'asc' : 'desc';

        $allowedSortColumns = ['created_at', 'name'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        return Company::query()
            ->when($request->globalSearch, fn ($q) => $q->where(
                fn ($q) => $q->whereRaw('LOWER(name) like ?', ['%' . strtolower($request->globalSearch) . '%'])
                    ->orWhereRaw('LOWER(email) like ?', ['%' . strtolower($request->globalSearch) . '%'])
            ))
            ->orderBy($sortBy, $sortOrder)
            ->paginate($request->pageSize)
            ->through(fn (Company $company) => CompanyResource::make($company));
    }
}
