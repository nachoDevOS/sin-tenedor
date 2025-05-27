<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ItemSaleController;

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





    Route::get('users/ajax/list', [UserController::class, 'list']);
    Route::post('users/store', [UserController::class, 'store'])->name('voyager.users.store');
    Route::put('users/{id}', [UserController::class, 'update'])->name('voyager.users.update');
    Route::delete('users/{id}/deleted', [UserController::class, 'destroy'])->name('voyager.users.destroy');



    Route::get('ajax/personList', [AjaxController::class, 'personList']);
    Route::post('ajax/person/store', [AjaxController::class, 'personStore']);

});


// Clear cache
Route::get('/admin/clear-cache', function() {
    Artisan::call('optimize:clear');
    return redirect('/admin/profile')->with(['message' => 'Cache eliminada.', 'alert-type' => 'success']);
})->name('clear.cache');