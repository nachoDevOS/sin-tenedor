<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vault;
use App\Models\Cashier;
use App\Models\CashierMovement;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\VaultDetail;
use App\Models\VaultDetailCash;
use Illuminate\Support\Facades\Auth;
use App\Traits\Loggable;

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
        $cashier = $this->cashierUserOpen($request->user_id);
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
        $cashier = $this->cashierId($id, null);        
        return view('cashiers.read' , compact('cashier'));
    }
}
