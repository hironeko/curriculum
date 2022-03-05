<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CompanyStoreRequest;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private Company $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function store(CompanyStoreRequest $request)
    {
        $params = $request->validated();
        $company = $this->company->create($params);
        return $company;
    }

    public function show(int $id)
    {
        $company = $this->company->findOrFail($id);

        return $company;
    }
}
