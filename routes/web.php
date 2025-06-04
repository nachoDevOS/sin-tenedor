<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\EgresInventoryController;
use App\Http\Controllers\ItemInventoryController;
use App\Http\Controllers\ItemSaleController;
use App\Http\Controllers\ReportInventoryController;
use App\Http\Controllers\ReportSaleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TableController;
use App\Models\EgresInventory;
use App\Models\EgresInventoryDetail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('login', function () {
    return redirect('admin/login');
})->name('login');

Route::get('/', function () {
    return redirect('admin');
});

Route::get('/development', [ErrorController::class , 'error503'])->name('development');

Route::group(['prefix' => 'admin', 'middleware' => ['loggin']], function () {
    Voyager::routes();

    Route::get('people', [PersonController::class, 'index'])->name('voyager.people.index');
    Route::get('people/ajax/list', [PersonController::class, 'list']);


    Route::get('item-sales', [ItemSaleController::class, 'index'])->name('voyager.item-sales.index');
    Route::get('item-sales/ajax/list', [ItemSaleController::class, 'list']);
    Route::get('item-sales/{id}', [ItemSaleController::class, 'show'])->name('voyager.item-sales.show');
    Route::post('item-sales/{id}/stock', [ItemSaleController::class, 'storeStock'])->name('item-sales-stock.store');
    Route::delete('item-sales/{id}/stock/{stock}', [ItemSaleController::class, 'destroyStock'])->name('item-sales-stock.destroy');


    Route::get('item-inventories', [ItemInventoryController::class, 'index'])->name('voyager.item-inventories.index');
    Route::get('item-inventories/ajax/list', [ItemInventoryController::class, 'list']);
    Route::get('item-inventories/{id}', [ItemInventoryController::class, 'show'])->name('voyager.item-inventories.show');
    Route::post('item-inventories/{id}/stock', [ItemInventoryController::class, 'storeStock'])->name('item-inventories-stock.store');
    Route::delete('item-inventories/{id}/stock/{stock}', [ItemInventoryController::class, 'destroyStock'])->name('item-inventories-stock.destroy');

    // Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
    Route::resource('sales', SaleController::class);
    Route::get('sales/ajax/list', [SaleController::class, 'list']);
    Route::get('sales/{id}/status', [SaleController::class, 'saleSuccess'])->name('sales-status.success');
    Route::get('sales/fullprint/{id}', [SaleController::class, 'fullPrint'])->name('sales-fullPrint.print');
    Route::get('sales/{id}/ticket', [SaleController::class, 'printTicket'])->name('sales-ticket.print');
    Route::get('sales/{id}/comanda', [SaleController::class, 'printComanda'])->name('sales-comanda.print');


    Route::resource('egres-inventories', EgresInventoryController::class);
    Route::get('egres-inventories/ajax/list', [EgresInventoryController::class, 'list']);
    Route::get('egres-inventories/stock/ajax', [EgresInventoryController::class, 'stockInventory']);//Para obtener los item que hay disponible en el inventario
    Route::get('egres-inventories/{id}/print', [EgresInventoryController::class, 'printEgres'])->name('egres-inventories.print');






    // Route::get('table-client', [TableController::class, 'indexClient'])->name('table-client.index');
    Route::get('boards/kitchen', [BoardController::class, 'index'])->name('boards-kitchen.index');





    Route::get('users/ajax/list', [UserController::class, 'list']);
    Route::post('users/store', [UserController::class, 'store'])->name('voyager.users.store');
    Route::put('users/{id}', [UserController::class, 'update'])->name('voyager.users.update');
    Route::delete('users/{id}/deleted', [UserController::class, 'destroy'])->name('voyager.users.destroy');
    

    Route::get('print-sale', [ReportSaleController::class, 'indexSale'])->name('print-sale.index');
    Route::post('print-sale/list', [ReportSaleController::class, 'listSale'])->name('print-sale.list');

    Route::get('print-sale/stock', [ReportSaleController::class, 'indexSaleStock'])->name('print-sale-stock.index');
    Route::post('print-sale/stock/list', [ReportSaleController::class, 'listSaleStock'])->name('print-sale-stock.list');

    Route::get('print-sale/income', [ReportSaleController::class, 'indexSaleIncome'])->name('print-sale-income.index');
    Route::post('print-sale/income/list', [ReportSaleController::class, 'listSaleIncome'])->name('print-sale-income.list');


// ########################## REPORTE DE INVENTARIO DEL ALMACEN ######################################
    Route::get('print-inventories/income', [ReportInventoryController::class, 'indexInventoryIncome'])->name('print-inventories-income.index');
    Route::post('print-inventories/income/list', [ReportInventoryController::class, 'listInventoryIncome'])->name('print-inventories-income.list');




    Route::get('ajax/personList', [AjaxController::class, 'personList']);
    Route::post('ajax/person/store', [AjaxController::class, 'personStore']);

});


// Clear cache
Route::get('/admin/clear-cache', function() {
    Artisan::call('optimize:clear');

    Artisan::call('db:seed', ['--class' => 'UpdateBreadSeeder']);
    Artisan::call('db:seed', ['--class' => 'UpdatePermissionsSeeder']);

    return redirect('/admin/profile')->with(['message' => 'Cache eliminada.', 'alert-type' => 'success']);
})->name('clear.cache');