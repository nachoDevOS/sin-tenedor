<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Pagination\Paginator;   
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\IndexController; 
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Paginator::useBootstrap();


        View::composer('*', function ($view) {
            $new = new IndexController();
            $controller = new Controller();


            $global_index = $new->IndexSystem();
            // $global_cashier = $global_cashier->availableMoney(Auth::user()->id, 'user');
            $view->with('global_index', $global_index->original); //Para retornar en formato json
            // $view->with('global_index', $global_index); //Para retornar en formato de array

            $global_cashierOpen = $controller->cashierOpen();
            $view->with('global_cashierOpenUser', $global_cashierOpen->original);

        });




    }
}
