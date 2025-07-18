<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LogisticController;

use App\Http\Controllers\LogistikuController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\CurrencyRateController;
use App\Http\Controllers\SupplierLocalController;
use App\Http\Controllers\SupplierOnlineController;
use App\Http\Controllers\QuotationSettingController;
use App\Http\Controllers\QuotationCalculatorController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('customers')->group(function () {
  Route::get('/', [CustomerController::class, 'index']);
  Route::post('/', [CustomerController::class, 'store']);
  Route::put('/{id}', [CustomerController::class, 'update']);
  Route::delete('/{id}', [CustomerController::class, 'destroy']);
});


Route::prefix('karyawans')->group(function () {
    Route::get('/', [KaryawanController::class, 'index']);
    Route::post('/', [KaryawanController::class, 'store']);
    Route::put('/{id_karyawan}', [KaryawanController::class, 'update']);
    Route::delete('/{id_karyawan}', [KaryawanController::class, 'destroy']);
});


Route::prefix('currencies')->group(function () {
    Route::get('/', [CurrencyController::class, 'index']);
    Route::post('/', [CurrencyController::class, 'store']);
    Route::put('/{id}', [CurrencyController::class, 'update']);
    Route::delete('/{id}', [CurrencyController::class, 'destroy']);
});

Route::prefix('currency-rates')->group(function () {
    Route::get('/', [CurrencyRateController::class, 'index']);
    Route::post('/', [CurrencyRateController::class, 'store']);
    Route::put('/{id}', [CurrencyRateController::class, 'update']);
    Route::delete('/{id}', [CurrencyRateController::class, 'destroy']);
    Route::get('/dropdown-data', [CurrencyRateController::class, 'dropdownData']);
});

Route::prefix('jenis')->group(function () {
    Route::get('/', [JenisController::class, 'getAll']);
    Route::post('/', [JenisController::class, 'store']);
    Route::put('/{id}', [JenisController::class, 'update']);
    Route::delete('/{id}', [JenisController::class, 'destroy']);
});

Route::prefix('kategoris')->group(function () {
    Route::get('/', [KategoriController::class, 'index']);
    Route::post('/', [KategoriController::class, 'store']);
    Route::put('/{id}', [KategoriController::class, 'update']);
    Route::delete('/{id}', [KategoriController::class, 'destroy']);
});

Route::prefix('warehouses')->group(function () {
    Route::get('/', [WarehouseController::class, 'index']);
    Route::post('/', [WarehouseController::class, 'store']);
    Route::put('/{id}', [WarehouseController::class, 'update']);
    Route::delete('/{id}', [WarehouseController::class, 'destroy']);
    Route::get('/dropdown-data', [WarehouseController::class, 'dropdownData']);
});

Route::prefix('suppliers-local')->group(function () {
    Route::get('/', [SupplierLocalController::class, 'index']);
    Route::post('/', [SupplierLocalController::class, 'store']);
    Route::put('/{id}', [SupplierLocalController::class, 'update']);
    Route::delete('/{id}', [SupplierLocalController::class, 'destroy']);
});

Route::prefix('suppliers-online')->group(function () {
    Route::get('/', [SupplierOnlineController::class, 'index']);
    Route::post('/', [SupplierOnlineController::class, 'store']);
    Route::put('/{id}', [SupplierOnlineController::class, 'update']);
    Route::delete('/{id}', [SupplierOnlineController::class, 'destroy']);
});

Route::prefix('logistics')->group(function () {
    Route::get('/', [LogisticController::class, 'index']);
    Route::post('/', [LogisticController::class, 'store']);
    Route::put('/{id}', [LogisticController::class, 'update']);
    Route::delete('/{id}', [LogisticController::class, 'destroy']);

});


Route::prefix('companies')->group(function () {
    Route::get('/', [CompanyController::class, 'index']);
    Route::post('/', [CompanyController::class, 'store']);
    Route::post('/{id}', [CompanyController::class, 'update']); // update pakai POST + _method=PUT
    Route::delete('/{id}', [CompanyController::class, 'destroy']);
});


Route::prefix('banks')->group(function () {
  Route::get('/', [BankController::class, 'index']);
  Route::post('/', [BankController::class, 'store']);
  Route::put('/{id}', [BankController::class, 'update']);
  Route::delete('/{id}', [BankController::class, 'destroy']);
});

Route::prefix('barangs')->group(function () {
  Route::get('/', [BarangController::class, 'index']);
  Route::post('/', [BarangController::class, 'store']);
  Route::put('/{id}', [BarangController::class, 'update']);
  Route::delete('/{id}', [BarangController::class, 'destroy']);
});


Route::prefix('logistikus')->group(function () {
    Route::get('/', [LogistikuController::class, 'index']);
    Route::post('/', [LogistikuController::class, 'store']);
    Route::put('/{id}', [LogistikuController::class, 'update']);
    Route::delete('/{id}', [LogistikuController::class, 'destroy']);
});

Route::prefix('quotation-settings')->group(function () {
    Route::get('/', [QuotationSettingController::class, 'index']);
    Route::post('/', [QuotationSettingController::class, 'store']);
    Route::put('/{id}', [QuotationSettingController::class, 'update']);
    Route::delete('/{id}', [QuotationSettingController::class, 'destroy']);
});

Route::prefix('quotation')->group(function () {
    Route::post('/calculate', [QuotationCalculatorController::class, 'calculate']);
});

