<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use Illuminate\Http\Request;
use App\Models\Vault;
use App\Models\VaultClosure;
use App\Models\VaultClosureDetail;
use App\Models\VaultDetail;
use App\Models\VaultDetailCash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VaultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()    
    {
        $this->custom_authorize('browse_vaults');
        $vault = Vault::with(['details.cash' => function($q){
                    $q->where('deleted_at', NULL);
                }, 'details' => function($q){
                    $q->where('deleted_at', NULL);
                }])->where('deleted_at', NULL)->first();
    
        return view('vaults.browse', compact('vault'));
    }

    //para crear una nueva boveda
    public function store(Request $request)
    {
        $this->custom_authorize('add_vaults');

        DB::beginTransaction();
        try {
            Vault::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'activa'
            ]);
            DB::commit();
            return redirect()->route('vaults.index')->with(['message' => 'Bóveda guardada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    //***para agregar ingreso y egreso a la boveda
    public function details_store(Request $request, $id){
        DB::beginTransaction();
        try {
            $detail = VaultDetail::create([
                'user_id' => Auth::user()->id,
                'vault_id' => $id,
                // 'bill_number' => $request->bill_number,
                'name_sender' => $request->name_sender,
                'description' => $request->description,
                'type' => $request->type,
                'status' => 'aprobado'
            ]);


            for ($i=0; $i < count($request->cash_value); $i++) { 
                // if($request->quantity[$i]){
                    VaultDetailCash::create([
                        'vault_detail_id' => $detail->id,
                        'cash_value' => $request->cash_value[$i],
                        'quantity' => $request->quantity[$i],
                    ]);
                // }
            }
            DB::commit();
            return redirect()->route('vaults.index')->with(['message' => 'Detalle de bóveda guardado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            
            $logMessage = [
                "🚨 ERROR CRÍTICO - Creación de Detalle de Bóveda",
                "==================================================",
                "📋 INFORMACIÓN GENERAL:",
                "   - ID: " . $id,
                "   - Usuario: " . Auth::user()->name . ' (ID: ' . (Auth::user()->id ?? 'N/A') . ')',
                "   - Fecha/Hora: " . now()->format('d/m/Y H:i:s'),
                "   - IP: " . $request->ip(),
                "   - URL: " . $request->fullUrl(),
                "--------------------------------------------------",
                "🔍 DETALLES DEL ERROR:",
                "   - Mensaje: " . $th->getMessage(),
                "   - Archivo: " . $th->getFile(),
                "   - Línea: " . $th->getLine(),
                "--------------------------------------------------",
                "📊 DATOS DE LA SOLICITUD (Payload):",
            ];

            // Obtener todos los datos de la solicitud, excluyendo campos sensibles.
            $requestData = $request->except(['password', 'password_confirmation', '_token', '_method']);
            if (!empty($requestData)) {
                foreach ($requestData as $key => $value) {
                    // Si el valor es un array, lo convertimos a JSON para una mejor visualización.
                    $formattedValue = is_array($value) ? json_encode($value) : $value;
                    $logMessage[] = "   - {$key}: {$formattedValue}";
                }
            }
            $logMessage[] = "==================================================";

            Log::error(implode(PHP_EOL, $logMessage));
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


    public function open($id, Request $request){
        // return 1111;
        DB::beginTransaction();
        try {

            Vault::where('id', $id)->update([
                'status' => 'activa',
                // 'closed_at' => Carbon::now()
            ]);
            DB::commit();
            return redirect()->route('vaults.index')->with(['message' => 'Bóveda abierta exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function close($id){
        return $id;
        $vault_closure = VaultClosure::with('details')->where('vault_id', $id)->orderBy('id', 'DESC')->first();
        $date = $vault_closure ? $vault_closure->created_at : NULL;
        // return $vault_closure;
        $vault = Vault::with(['details' => function($q) use($date){
                        if($date){
                            $q->where('created_at', '>', $date);
                        }
                    }, 'details.cash', 'details.cashier.user'])
                    ->where('status', 'activa')->where('id', $id)->where('deleted_at', NULL)->first();
        // dd($vault);
        // return $vault;
        return view('vaults.close', compact('vault', 'vault_closure'));
    } 

    //***Para guardar cuando se cierre de boveda
    public function close_store($id, Request $request){
        $cashier_open = Cashier::whereRaw("status = 'abierta' or status = 'apertura pendiente'")->where('deleted_at', NULL)->count();
        // return $cashier_open;
        if($cashier_open){
            return redirect()->route('vaults.index')->with(['message' => 'No puedes cerrar bóveda porque existe una caja abierta.', 'alert-type' => 'error']);
        }

        DB::beginTransaction();
        try {

            Vault::where('id', $id)->update([
                'status' => 'cerrada',
                'closed_at' => Carbon::now()
            ]);

            $vault_closure = VaultClosure::create([
                'vault_id' => $id,
                'user_id' => Auth::user()->id,
                'observations' => $request->observations
            ]);

            for ($i=0; $i < count($request->cash_value); $i++) { 
                VaultClosureDetail::create([
                    'vault_closure_id' => $vault_closure->id,
                    'cash_value' => $request->cash_value[$i],
                    'quantity' => $request->quantity[$i],
                ]);
            }
            DB::commit();
            return redirect()->route('vaults.index')->with(['message' => 'Bóveda cerrada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd(0);
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    // ***para imprimir Boveda en General
    public function print_status($id){
        $vault = Vault::with(['user', 'details.cash' => function($q){
            $q->where('deleted_at', NULL);
        }, 'details' => function($q){
            $q->where('deleted_at', NULL);
        }])->where('id', $id)->where('deleted_at', NULL)->first();
        return view('vaults.print.print-vaults', compact('vault'));
    }
}
