<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CompanyBillingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('todo/create', [TodoController::class, 'store'])->name('api.todo.create');
Route::get('todo/{id}', [TodoController::class, 'show'])->name('api.todo.show');
Route::put('todo/{id}', [TodoController::class, 'update'])->name('api.todo.update');
Route::delete('todo/{id}', [TodoController::class, 'destroy'])->name('api.todo.destroy');

Route::post('company/create', [CompanyController::class, 'store'])->name('api.company.create');
Route::get('company/{id}', [CompanyController::class, 'show'])->name('api.company.show');
Route::put('company/{id}', [CompanyController::class, 'update'])->name('api.company.update');
Route::delete('company/{id}', [CompanyController::class, 'delete'])->name('api.company.delete');
Route::post('company/create-with-billing', [CompanyController::class, 'storeWithBilling'])->name('api.company.create.with_billing');

Route::post('company/{id}/billing/create', [CompanyBillingController::class, 'store'])->name('api.company.billing.create');
Route::get('company-billing/{id}', [CompanyBillingController::class, 'show'])->name('api.company.billing.show');
Route::put('company-billing/{id}', [CompanyBillingController::class, 'update'])->name('api.company.billing.update');
Route::delete('company-billing/{id}', [CompanyBillingController::class, 'delete'])->name('api.company.billing.delete');
