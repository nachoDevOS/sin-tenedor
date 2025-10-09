<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\EgresInventoryController;
use App\Http\Controllers\ItemInventoryController;
use App\Http\Controllers\ItemSaleController;
use App\Http\Controllers\ReportInventoryController;
use App\Http\Controllers\ReportSaleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\VaultController;
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

Route::get('/info/{id?}', [ErrorController::class , 'error'])->name('errors');
// Route::get('/500', [ErrorController::class , 'error500']);

Route::group(['prefix' => 'admin', 'middleware' => ['loggin', 'system']], function () {
    Voyager::routes();


    Route::resource('vaults', VaultController::class);

    Route::post('vaults/{id}/details/store', [VaultController::class, 'details_store'])->name('vaults.details.store');//***para agregar ingreso y egreso a la boveda
    Route::post('vaults/{id}/open', [VaultController::class, 'open'])->name('vaults.open');
    Route::get('vaults/{id}/close', [VaultController::class, 'close'])->name('vaults.close');
    Route::post('vaults/{id}/close/store', [VaultController::class, 'close_store'])->name('vaults.close.store');//***Para guardar cuando se cierre de boveda
    Route::get('vaults/{vault}/print/status', [VaultController::class, 'print_status'])->name('vaults.print.status');//***

    Route::resource('cashiers', CashierController::class);
    Route::get('cashiers/list/ajax', [CashierController::class, 'list'])->name('cashiers.list');
    // Route::get('cashiers/{cashier}/amount', [CashierController::class, 'amount'])->name('cashiers.amount');//para abrir la vista de poder agregar dinero o aboinar mas dinero a la caja
    // Route::post('cashiers/amount/store', [CashierController::class, 'amount_store'])->name('cashiers.amount.store');//para guardar el monto adicional de abonar dinero a la caja cuando este abierta
    // Route::post('cashiers/amount/transfer/store', [CashierController::class, 'amountTransferStore'])->name('cashiers-amount-transfer.store');//para poder transferir dinero a otra caja de manera sensilla
    // Route::delete('cashiers/{cashier_id}/amount/transfer/{transfer_id}/delete', [CashierController::class, 'cashierAmountTransferDetele'])->name('cashiers-amount-transfer.delete');//para poder eliminar la transferencia
    // Route::get('cashiers/{cashier_id}/transfer/{transfer_id}/success', [CashierController::class, 'amountTransferSuccess'])->name('cashiers-transfer.success');//para poder aceptar la transferencia
    // Route::get('cashiers/{cashier_id}/transfer/{transfer_id}/decline', [CashierController::class, 'amountTransferDecline'])->name('cashiers-transfer.decline');//para poder rechazar la transferencia
    
    // Route::post('cashiers/expense/store', [CashierController::class, 'expense_store'])->name('cashiers.expense.store'); // Agregar gasto
    // Route::delete('cashiers/{cashier}/expense/{expense}/delete', [CashierController::class, 'cashierExpenseDelete'])->name('cashiers-expense.delete'); // Agregar gasto

    Route::post('cashiers/{cashier}/change/status', [CashierController::class, 'change_status'])->name('cashiers.change.status');//*** Para que los cajeros Acepte o rechase el dinero dado por Boveda o gerente
    Route::get('cashiers/{cashier}/close/', [CashierController::class, 'close'])->name('cashiers.close');//***para cerrar la caja el cajero vista 
    Route::post('cashiers/{cashier}/close/store', [CashierController::class, 'close_store'])->name('cashiers.close.store'); //para que el cajerop cierre la caja 
    Route::post('cashiers/{cashier}/close/revert', [CashierController::class, 'close_revert'])->name('cashiers.close.revert'); //para revertir el cajero para q su caja vuelva 
    Route::get('cashiers/{cashier}/confirm_close', [CashierController::class, 'confirm_close'])->name('cashiers.confirm_close'); //Para confirmar el cierre de caja
    Route::post('cashiers/{cashier}/confirm_close/store', [CashierController::class, 'confirm_close_store'])->name('cashiers.confirm_close.store');

    Route::get('cashiers/print/open/{id?}', [CashierController::class, 'print_open'])->name('print.open');//para imprimir el comprobante cuando se abre una caja
    Route::get('cashiers/print/close/{id?}', [CashierController::class, 'print_close'])->name('print.close');//Para imprimir cierre de caja
    Route::get('cashiers/{id}/print', [CashierController::class, 'print'])->name('cashiers.print');//Para el cierre pendiente de caja por el cajero

    // Route::delete('cashiers/{cashier}/loans/transaction/{transaction}/delete', [CashierController::class, 'deleteTransaction'])->name('cashiers-loan.transaction.delete');//Para eliminar pagos de prestamos sin prenda
    // Route::delete('cashiers/{cashier}loans/{loan}/delete', [CashierController::class, 'deleteLoan'])->name('cashiers-loan.delete');//para pider eliminar prestamos cuando no tenga dias pagados 


    // Route::delete('cashiers/{cashier}/pawn/transaction/{transaction}/delete', [CashierController::class, 'pawnTransactionDelete'])->name('cashiers-pawn-transaction.delete');//Para aliminar pagos con prendas
    // Route::delete('cashiers/{cashier}/pawn/{pawn}/delete', [CashierController::class, 'pawnDelete'])->name('cashier-pawn.delete');//Para eliminar un prestamo "prendario" de una caja abierta
    // Route::delete('cashiers/{cashier}/pawn/aditional/{aditional}/delete', [CashierController::class, 'pawnAmountAditionalDelete'])->name('cashier-pawn-aditional.delete');//Para eliminar el monto adicional que se da por la prenda con la caja abierta

    // Route::delete('cashiers/{cashier}/salaryPurchase/{salary}/delete', [CashierController::class, 'salaryPurchaseDelete'])->name('cashiers-salaryPurchase.delete');//Para eliminar un prestamo "prestamo de sueldo" de una caja abierta
    // Route::delete('cashiers/{cashier}/salaryPurchase/transaction/{transaction}/delete', [CashierController::class, 'salaryPurchaseTransactionDelete'])->name('cashiers-salaryPurchase-transaction.delete');//Para aliminar pagos con prendas


    // Route::delete('cashiers/{cashier}/sale/{saleAgent}/delete', [CashierController::class, 'cashierSaleDelete'])->name('cashier-sale.delete');//Para eliminar una venta al contado y credito como eliminar pagos al credito de una caja abierta




    Route::get('people', [PersonController::class, 'index'])->name('voyager.people.index');
    Route::get('people/ajax/list', [PersonController::class, 'list']);
    Route::post('people', [PersonController::class, 'store'])->name('voyager.people.store');
    Route::put('people/{id}', [PersonController::class, 'update'])->name('voyager.people.update');


    Route::get('item-sales', [ItemSaleController::class, 'index'])->name('voyager.item-sales.index');
    Route::get('item-sales/ajax/list', [ItemSaleController::class, 'list']);
    Route::post('item-sales', [ItemSaleController::class, 'store'])->name('voyager.item-sales.store');
    Route::put('item-sales/{id}', [ItemSaleController::class, 'update'])->name('voyager.item-sales.update');

    Route::get('item-sales/{id}', [ItemSaleController::class, 'show'])->name('voyager.item-sales.show');
    Route::post('item-sales/{id}/stock', [ItemSaleController::class, 'storeStock'])->name('item-sales-stock.store');
    Route::delete('item-sales/{id}/stock/{stock}', [ItemSaleController::class, 'destroyStock'])->name('item-sales-stock.destroy');


    Route::get('item-inventories', [ItemInventoryController::class, 'index'])->name('voyager.item-inventories.index');
    Route::get('item-inventories/ajax/list', [ItemInventoryController::class, 'list']);
    Route::post('item-inventories', [ItemInventoryController::class, 'store'])->name('voyager.item-inventories.store');
    Route::put('item-inventories/{id}', [ItemInventoryController::class, 'update'])->name('voyager.item-inventories.update');
    Route::get('item-inventories/{id}', [ItemInventoryController::class, 'show'])->name('voyager.item-inventories.show');
    Route::post('item-inventories/{id}/stock', [ItemInventoryController::class, 'storeStock'])->name('item-inventories-stock.store');
    Route::delete('item-inventories/{id}/stock/{stock}', [ItemInventoryController::class, 'destroyStock'])->name('item-inventories-stock.destroy');

    // Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
    Route::resource('sales', SaleController::class);
    Route::get('sales/ajax/list', [SaleController::class, 'list']);
    Route::get('sales/{id}/status', [SaleController::class, 'saleSuccess'])->name('sales-status.success');
    // Route::get('sales/fullprint/{id}', [SaleController::class, 'fullPrint'])->name('sales-fullPrint.print');
    Route::get('sales/ticket/{id}', [SaleController::class, 'printTicket'])->name('sales-ticket.print');
    // Route::get('sales/{id}/comanda', [SaleController::class, 'printComanda'])->name('sales-comanda.print');


    Route::resource('egres-inventories', EgresInventoryController::class);
    Route::get('egres-inventories/ajax/list', [EgresInventoryController::class, 'list']);
    Route::get('egres-inventories/stock/ajax', [EgresInventoryController::class, 'stockInventory']);//Para obtener los item que hay disponible en el inventario
    Route::get('egres-inventories/{id}/print', [EgresInventoryController::class, 'printEgres'])->name('egres-inventories.print');






    // Route::get('table-client', [TableController::class, 'indexClient'])->name('table-client.index');
    Route::get('boards/kitchen', [BoardController::class, 'index'])->name('boards-kitchen.index');




    // Usuarios 
    Route::get('users/ajax/list', [UserController::class, 'list']);
    Route::post('users/store', [UserController::class, 'store'])->name('voyager.users.store');
    Route::put('users/{id}', [UserController::class, 'update'])->name('voyager.users.update');
    Route::delete('users/{id}/deleted', [UserController::class, 'destroy'])->name('voyager.users.destroy');

    // Roles
    Route::get('roles/ajax/list', [RoleController::class, 'list']);

    


// ################################ REPORTE DE VENTAS #####################################
    Route::get('report/sales', [ReportSaleController::class, 'indexSale'])->name('report-sales.index');
    Route::post('report/sales/list', [ReportSaleController::class, 'listSale'])->name('report-sales.list');

    Route::get('report/sales-stock', [ReportSaleController::class, 'indexSaleStock'])->name('report-sales-stock.index');
    Route::post('report/sales/stock/list', [ReportSaleController::class, 'listSaleStock'])->name('report-sales-stock.list');

    Route::get('report/sales-income', [ReportSaleController::class, 'indexSaleIncome'])->name('report-sales-income.index');
    Route::post('report/sales/income/list', [ReportSaleController::class, 'listSaleIncome'])->name('report-sales-income.list');


// ########################## REPORTE DE INVENTARIO DEL ALMACEN ######################################
    Route::get('report/inventories-egres', [ReportInventoryController::class, 'indexInventoryEgres'])->name('report-inventories-egres.index');
    Route::post('report/inventories/egres/list', [ReportInventoryController::class, 'listInventoryEgres'])->name('report-inventories-egres.list');

    Route::get('report/inventories-stock', [ReportInventoryController::class, 'indexInventoryStock'])->name('report-inventories-stock.index');
    Route::post('report/inventories/stock/list', [ReportInventoryController::class, 'listInventoryStock'])->name('report-inventories-stock.list');

    Route::get('report/inventories-income', [ReportInventoryController::class, 'indexInventoryIncome'])->name('report-inventories-income.index');
    Route::post('report/inventories/income/list', [ReportInventoryController::class, 'listInventoryIncome'])->name('report-inventories-income.list');




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