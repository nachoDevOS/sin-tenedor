<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use DateTime;
use App\Models\Cashier;

use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function custom_authorize($permission){
        if(!Auth::user()->hasPermission($permission)){
            abort(403, 'THIS ACTIO UNAUTHORIZED.');
        }
    }

    // Funcion para ver la caja en estado abierta
    public function cashierOpen()
    {
        $cashier = Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('user_id', Auth::user()->id)
        ->where('status', 'abierta')
        ->where('deleted_at', NULL)->first();

        // return $cashierOpen;
        return response()->json(['return' => $cashier]);

    }


    // Funcion para ver la caja en estado abierta
    public function cashierUserOpen($user_id, $status)
    {
        $cashier = Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('user_id', $user_id)
        ->whereRaw($status?'status = "'.$status.'"':1)
        // ->where('status', 'abierta')
        ->where('deleted_at', NULL)->first();

        return response()->json(['return' => $cashier]);
    }

    //Para obtener el detalle de cualquier caja y en cualquier estado que no se encuentre eliminada (id de la caja, Estado de la caja)
    public function cashierId($id, $status)
    {
        return Cashier::with([
            'movements',
            // 'details' => function($q){
            //     $q->where('deleted_at', NULL);
            // },
            // 'loan_payments' => function($q){                
            //     $q->whereHas('transaction', function($q) {
            //         $q->whereIn('type', ['Efectivo', 'Qr']);
            //     })
            //     ->with(['loanDay.loan.people', 'agent']);
            // },
            // 'loans' => function($q){
            //     $q->with(['people'])
            //     ->where('status', 'entregado');
            // },
            // 'pawn' => function($q){
            //     $q->with(['person', 'details.featuresLists', 'details.type', 'user']); // Cargar la relación 'people' dentro de 'pawn'
            // },            
            // 'pawnMoneyAditional' => function($q){          //Para los aumento en algunos prestamos     
            //     $q->with(['pawnRegister.person']);
            // },
            // 'pawnPayment' => function($q) {
            //     $q->whereHas('transaction', function($q) {
            //         $q->whereIn('type', ['Efectivo', 'Qr']);
            //     })
            //     ->with(['pawnRegister.person', 'agent']);
            // },
            // 'salePayment' => function($q) {
            //     $q->whereHas('transaction', function($q) {
            //             $q->whereIn('type', ['Efectivo', 'Qr']);
            //         })
            //         ->with(['sale.person', 'register']);
            // },

            // 'salaryPurchase' => function($q){ //Para obtener los prestamos que se leentregan a los maestros
            //     $q->with(['person']);
            // },

            // 'salaryPurchasePayment' => function($q) {
            //     $q->whereHas('transaction', function($q) {
            //         $q->whereIn('type', ['Efectivo', 'Qr']);
            //     })
            //     ->with(['salaryPurchase.person', 'agent']);
            // },

            'user'
        ])
        ->where('id', $id)
        ->where('deleted_at', null)
        ->whereRaw($status?'status = "'.$status.'"':1)
        ->first();        
    }





    

}
