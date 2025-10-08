<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vault;
use App\Models\Cashier;
use App\Models\CashierDetail;
use App\Models\CashierMovement;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\VaultDetail;
use App\Models\VaultDetailCash;
use Illuminate\Support\Facades\Auth;
use App\Traits\Loggable;
use Illuminate\Support\Carbon;

class CashierController extends Controller
{
    use Loggable;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->custom_authorize('browse_cashiers');
        return view('cashiers.browse');
    }

    public function list()
    {
        $this->custom_authorize('browse_cashiers');

        $paginate = request('paginate') ?? 10;
        $search = request('search') ?? null;
        $status = request('status') ?? null;
        $cashier =  Cashier::with([
                        'vault_details' => function ($q) {
                            $q->where('type', 'egreso')->where('deleted_at', null);
                        },
                        'movements' => function ($q) {
                            $q->where('deleted_at', null);
                        },
                    ])
                    ->where(function ($query) use ($search) {
                        if ($search) {
                            $query->OrwhereHas('user', function ($query) use ($search) {
                                $query->whereRaw("name like '%$search%'");
                            });
                        }
                    })
                    ->where('deleted_at', null)
                    ->orderBy('id', 'DESC')
                    ->paginate($paginate);

        // dump($cashier);
        // return 1;
            
        return view('cashiers.list', compact('cashier'));
    }

    public function create()
    {
        $this->custom_authorize('add_cashiers');
        $vault = Vault::with(['details.cash'])
            ->where('status', 'activa')
            ->where('deleted_at', null)
            ->first();
        $cashiers = User::where('role_id', '!=', 1)->where('status', 1)->get();
        return view('cashiers.add', compact('vault', 'cashiers'));
    }
    public function store(Request $request)
    {
        $this->custom_authorize('add_cashiers');
        $cashier = $this->cashier('user', $request->user_id, 'status = "abierta" or status = "apertura pendiente"');

        if ($cashier) {
            return redirect()
                ->route('cashiers.index')
                ->with(['message' => 'El usuario seleccionado tiene una caja que no ha sido cerrada.', 'alert-type' => 'warning']);
        }

        DB::beginTransaction();
        try {
            $cashier = Cashier::create([
                'vault_id' => $request->vault_id,
                'user_id' => $request->user_id,
                'title' => $request->title,
                'observations' => $request->observations,
                'status' => 'apertura pendiente',
            ]);

            CashierMovement::create([
                'cashier_id' => $cashier->id,
                'amount' => $request->amount ? $request->amount : 0,
                'description' => 'Monto de apertura de caja.',
                'type' => 'ingreso',
                'status' => 'Aceptado',
            ]);

            // Registrar detalle de bóveda
            $detail = VaultDetail::create([
                'vault_id' => $request->vault_id,
                'cashier_id' => $cashier->id,
                'description' => 'Traspaso a ' . $cashier->title.', Por apertura de caja',
                'type' => 'egreso',
                'status' => 'aprobado',
            ]);

            for ($i = 0; $i < count($request->cash_value); $i++) {
                VaultDetailCash::create([
                    'vault_detail_id' => $detail->id,
                    'cash_value' => $request->cash_value[$i],
                    'quantity' => $request->quantity[$i],
                ]);
            }
            DB::commit();
            return redirect()->route('cashiers.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->logError($th, $request);
            return redirect()->route('cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function print_open($id){
        $vaultDeatil = VaultDetail::where('id', $id)->where('deleted_at', null)->first();
        $aux = $id;
        $cashier = Cashier::with(['user', 'vault_detail' => function($q) use($aux){
                $q->where('id', $aux)->where('deleted_at', NULL);
            }, 'vault_detail.cash' => function($q){
                $q->where('deleted_at', NULL);
            }, 'movements' => function($q){
                $q->where('deleted_at', NULL);
            }])
            ->where('id', $vaultDeatil->cashier_id)->first();

      
        return view('cashiers.print-open', compact('cashier'));
    }
    

    public function show($id)
    {
        $cashier = $this->cashier('cashier', $id, null);        
        return view('cashiers.read' , compact('cashier'));
    }


    //*** Para que los cajeros Acepte o rechase el dinero dado por Boveda o gerente
    public function change_status($id, Request $request){
        DB::beginTransaction();
        try {
            if($request->status == 'abierta'){
                $message = 'Caja aceptada exitosamente.';
                Cashier::where('id', $id)->update([
                    'status' => $request->status,
                    'view' => Carbon::now()
                ]);
            }else{
                $cashier = Cashier::with(['vault_detail.cash' => function($q){
                    $q->where('deleted_at', NULL);
                }])->where('id', $id)->first();

                $message = 'Caja rechazada exitosamente.';
                Cashier::where('id', $id)->update([
                    'status' => 'rechazada',
                    'deleted_at' => Carbon::now(),
                    'deleteUser_id' => Auth::user()->id,
                    'deleteRole' => Auth::user()->role->name,
                ]);

                $vault_detail = VaultDetail::create([
                    // 'user_id' => Auth::user()->id,
                    'vault_id' => $cashier->vault_detail->vault_id,
                    'cashier_id' => $cashier->id,
                    'description' => 'Rechazo de apertura de caja de '.$cashier->title.'.',
                    'type' => 'ingreso',
                    'status' => 'aprobado'
                ]);

                foreach ($cashier->vault_detail->cash as $item) {
                    if($item->quantity > 0){
                        VaultDetailCash::create([
                            'vault_detail_id' => $vault_detail->id,
                            'cash_value' => $item->cash_value,
                            'quantity' => $item->quantity
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('voyager.dashboard')->with(['message' => $message, 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->logError($th, $request);
            return redirect()->route('voyager.dashboard')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


    //***para cerrar la caja el cajero vista 
    public function close($id)
    {
        $cashier = $this->cashier('cashier', $id, 'status = "abierta"');

        if (!$cashier) {
            return redirect()->route('voyager.dashboard')->with(['message' => 'La caja no se encuentra abierta.', 'alert-type' => 'warning']);
        }
        if (count($cashier->movements->where('deleted_at', null)->where('status', 'Pendiente'))>0) {
            return redirect()->route('voyager.dashboard')->with(['message' => 'La caja no puede ser cerrada, tiene transacciones pendiente.', 'alert-type' => 'warning']);
        }        
        return view('cashiers.close', compact('cashier'));
    }

    public function close_store(Request $request, $id){
        // return $request;
        DB::beginTransaction();
        try {
            $cashier = Cashier::findOrFail($id);
            if($cashier->status != 'cierre pendiente'){
                // $cashier->amount = $request->amount_cashier;
                // $cashier->amount_real = $request->amount_real;
                // $cashier->balance = $request->amount_real - $request->amount_cashier;
                $cashier->closed_at = Carbon::now();
                $cashier->status = 'cierre pendiente';
                $cashier->save();

                for ($i=0; $i < count($request->cash_value); $i++) { 
                    CashierDetail::create([
                        'cashier_id' => $id,
                        'cash_value' => $request->cash_value[$i],
                        'quantity' => $request->quantity[$i],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('voyager.dashboard')->with(['message' => 'Caja cerrada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->logError($th, $request);
            return redirect()->route('voyager.dashboard')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
    public function close_revert($id, Request $request){
        DB::beginTransaction();
        try {
            $cashier = Cashier::findOrFail($id);
            if($cashier->status == 'cierre pendiente'){
                $cashier->closed_at = NULL;
                $cashier->status = 'abierta';
                $cashier->save();

                CashierDetail::where('cashier_id', $id)->update([
                    'deleted_at' => Carbon::now()
                ]);

                DB::commit();
                return redirect()->route('voyager.dashboard')->with(['message' => 'Caja reabierta exitosamente.', 'alert-type' => 'success']);
            }

            return redirect()->route('voyager.dashboard')->with(['message' => 'Lo siento, su caja ya fué cerrada.', 'alert-type' => 'warning']);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->logError($th, $request);
            return redirect()->route('voyager.dashboard')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function print($id){

        $cashier = Cashier::with(['details' => function($q){
                        $q->where('deleted_at', NULL);
                    }, 'sales' => function($q){
                        $q->with(['person', 'register', 'saleDetails', 'saleTransactions']);
                    }])
                    ->where('id', $id)
                    ->first();
        return $cashier;


        return view('cashiers.print-close-details', compact('cashier',));
    }

    public function confirm_close($id)
    {
        $cashier = $this->cashier('cashier', $id, '');

        if($cashier->status == 'cierre pendiente'){
            return view('cashiers.confirm_close', compact('cashier'));
        }else{
            return redirect()->route('cashiers.index')->with(['message' => 'La caja ya no está abierta.', 'alert-type' => 'warning']);
        }
    }

    public function confirm_close_store($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $cashier = Cashier::findOrFail($id);
            $cashier->status = 'cerrada';
            $cashier->closeUser_id= Auth::user()->id;
            $cashier->save();
            
            $detail = VaultDetail::create([
                // 'user_id' => Auth::user()->id,
                'cashier_id' => $id,
                'vault_id' => $request->vault_id,
                'description' => 'Devolución de la caja '.$cashier->title,
                'type' => 'ingreso',
                'status' => 'aprobado'
            ]);

            for ($i=0; $i < count($request->cash_value); $i++) { 
                VaultDetailCash::create([
                    'vault_detail_id' => $detail->id,
                    'cash_value' => $request->cash_value[$i],
                    'quantity' => $request->quantity[$i],
                ]);
            }

            DB::commit();
            return redirect()->route('cashiers.index')->with(['message' => 'Caja cerrada exitosamente.', 'alert-type' => 'success', 'id_cashier_close' => $id]);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function print_close($id){
        $cashier = Cashier::with(['user','userclose',
                    'movements' => function($q){
                        $q->where('deleted_at', NULL);
                    }, 'details' => function($q){
                        $q->where('deleted_at', NULL);
                    }])
                    ->where('id', $id)->first();

        return view('cashiers.print-close', compact('cashier'));
    }

}
