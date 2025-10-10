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
    // public function cashierOpen()
    // {
    //     $cashier = Cashier::with(['movements' => function($q){
    //         $q->where('deleted_at', NULL);
    //     }])
    //     // ->where('user_id', Auth::user()->id)
    //     // ->where('status', 'abierta')
    //     ->where('deleted_at', NULL)->first();

    //     return $cashier;
    //     // return response()->json(['cashier' => $cashier]);

    // }


    // Funcion para ver la caja en estado abierta
    // public function cashiers($user_id, $status)
    // {
    //     $cashier = Cashier::with(['movements' => function($q){
    //         $q->where('deleted_at', NULL);
    //     }])
    //     ->where('user_id', $user_id)
    //     ->whereRaw($status?'status = "'.$status.'"':1)
       
    //     ->where('deleted_at', NULL)->first();

    //     return $cashier;
    // }

    //Para obtener el detalle de cualquier caja y en cualquier estado que no se encuentre eliminada (Tipo de ID, cashier_id o user_id , status)
    public function cashier($type, $id, $status)
    {
        $query = 'id = '.$id;
        if($type == 'user'){
            $query = 'user_id = '.$id;
        }

        $cashier = Cashier::with(['movements' => function($q){
                            $q->where('deleted_at', NULL);
                        },'vault_details.cash' => function($q){
                            $q->where('deleted_at', NULL);
                        },'sales' => function($q) {                
                            $q->whereHas('saleTransactions', function($q) {
                                $q->whereIn('paymentType', ['Efectivo', 'Qr']);
                            })
                            ->with(['saleTransactions' => function($q) {
                                $q->where('deleted_at', NULL);
                            }]);
                        },
                    ])
                    ->whereRaw($id?$query:1)
                    ->where('deleted_at', null)
                    ->whereRaw($status?$status:1)
                    ->first();    
        
        return $cashier;
    }

    public function cashierMoney($type, $id, $status)
    {
        $cashier = $this->cashier($type, $id, $status);


        if($cashier){
            $cashierIn = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->where('status', 'Aceptado')->sum('amount');

            //::::::::::::Ingresos::::::::::
            // $paymentEfectivo = $cashier->sales->where('deleted_at', NULL)->where('saleTransactions.typeSale', 'Efectivo')->sum('amount');


            $paymentEfectivo = $cashier->sales
                ->flatMap(function($sale) {
                    return $sale->saleTransactions->where('paymentType', 'Efectivo')->pluck('amount');
                })
                ->sum();

            $paymentQr = $cashier->sales
                ->flatMap(function($sale) {
                    return $sale->saleTransactions->where('paymentType', 'Qr')->pluck('amount');
                })
                ->sum();

            $cashierOut =0;



            $amountCashier = ($cashierIn + $paymentEfectivo) - $cashierOut;
        }

        return response()->json([
            'return' => $cashier?true:false,
            'cashier' => $cashier?$cashier:null,
            // // datos en valores
            'paymentEfectivo' => $cashier?$paymentEfectivo:null,//Para obtener el total de dinero en efectivo recaudado en general
            'paymentQr' => $cashier?$paymentQr:null, //Para obtener el total de dinero en QR recaudado en general
            'amountCashier'=>$cashier?$amountCashier:null, //dinero disponible en caja para su uso 'solo dinero que hay en la caja disponible y cobro solo en efectivos'

            // 'amountEgres' =>$cashier?$amountEgres:null, // dinero prestado de prenda y diario

            'cashierOut'=>$cashier?$cashierOut:null, //Gastos Adicionales

            'cashierIn'=>$cashier?$cashierIn:null// Dinero total abonado a las cajas
        ]);
    }





    

}
