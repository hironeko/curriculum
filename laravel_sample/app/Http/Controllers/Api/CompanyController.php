<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CompanyStoreRequest;
use App\Http\Requests\Api\CompanyStoreWithBillingRequest;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

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

    public function update(CompanyStoreRequest $request, int $id)
    {
        $params = $request->validated();
        $company = $this->company->findOrFail($id);

        $company->update($params);

        return $company;
    }

    public function delete(int $id)
    {
        $this->company->findOrFail($id)->delete();
        return ['message' => 'success'];
    }

    public function storeWithBilling(CompanyStoreWithBillingRequest $request)
    {
        $params = $request->validated();

        $company = DB::transaction(function() use ($params) {
            $company = $this->company->create($params);
            $company->billing()->create($params['billing']);
            return $company->load('billing');
        });

        return $company;
    }
}
