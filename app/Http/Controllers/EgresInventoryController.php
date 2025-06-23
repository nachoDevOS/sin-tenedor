<?php

namespace App\Http\Controllers;

use App\Models\EgresDetailItemInventoryStock;
use App\Models\EgresInventory;
use App\Models\EgresInventoryDetail;
use App\Models\ItemInventory;
use App\Models\ItemInventoryStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EgresInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->custom_authorize('browse_egres_inventories');
        return view('egres-inventories.browse');
    }

    public function list(){
        $this->custom_authorize('browse_egres_inventories');

        $search = request('search') ?? null;
        $paginate = request('paginate') ?? 10;
        $status = request('status') ?? null;
        $typeSale = request('typeSale') ?? null;

        $data = EgresInventory::with(['register'])
                        ->where(function($query) use ($search){
                            $query->OrWhereRaw($search ? "id = '$search'" : 1)
                            ->OrWhereRaw($search ? "code like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "observation like '%$search%'" : 1);
                        })
                        ->where('deleted_at', NULL)
                        // ->whereRaw($status? "status = '$status'" : 1)
                        ->orderBy('id', 'DESC')
                        ->paginate($paginate);

        return view('egres-inventories.list', compact('data'));
    }
    public function show($id)
    {
        $egres = EgresInventory::with(['register','egresInventoryDetails'=>function($q){
                $q->where('deleted_at', null);
            }])
            ->where('id', $id)
            ->where('deleted_at', null)
            ->first();
        // return $egres;

        return view('egres-inventories.read',compact('egres'));
    }

    public function create()
    {
        $this->custom_authorize('add_egres_inventories');
        return view('egres-inventories.edit-add');
    }

    public function stockInventory(){
        $search = request('q');

        $data = ItemInventory::with(['itemInventoryStocks', 'category'])
            ->Where(function($query) use ($search){
                if($search){
                    $query->OrwhereHas('category', function($query) use($search){
                                $query->whereRaw($search ? "name like '%$search%'" : 1);
                        })
                        ->OrWhereRaw($search ? "id = '$search'" : 1)
                        ->OrWhereRaw($search ? "name like '%$search%'" : 1)
                        ->OrWhereRaw($search ? "observation like '%$search%'" : 1);
                }
            })
            ->where('deleted_at', null)
            ->where('status', 1)
            ->whereHas('itemInventoryStocks', function($query) {
                $query->where('stock', '>', 0)
                ->where('deleted_at', null);
            })
            ->withSum('itemInventoryStocks as total_stock', 'stock') // Suma el stock y lo guarda en `total_stock`
            ->having('total_stock', '>', 0) // Filtra solo los que tengan suma > 0
            ->get();            
    
        return response()->json($data);
    }

    public function store(Request $request)
    {
        // return $request;
        $this->custom_authorize('add_egres_inventories');
        $ok = false;
        foreach ($request->products as $key => $value) {
            $item = ItemInventory::with(['itemInventoryStocks'=>function($q){
                    $q->where('deleted_at', null)
                    ->where('stock', '>', 0);
                }])
                ->where('deleted_at', null)
                ->where('status', 1)
                ->where('id', $value['id'])
                ->first();
            $auxStock = $item->itemInventoryStocks->sum('stock');       

            if ($value['dispensingType'] != $item->dispensingType || $value['quantity'] > $auxStock) {
                return redirect()->route('egres-inventories.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            }
        }


        DB::beginTransaction();
        try {
            $egres = EgresInventory::create([
                'dateEgres'=>Carbon::now(),
                'observation'=>$request->observation,
                'status'=>'Entregado'
            ]);

            foreach ($request->products as $key => $value) {

                $egresDetail = EgresInventoryDetail::create([
                    'egresInventory_id'=>$egres->id,
                    'item_id'=>$value['id'],
                    'dispensingType'=>$value['dispensingType'],
                    'quantity'=>$value['quantity'],
                    'observation'=>$value['observation']
                ]);

                $aux = $value['quantity'];
                $cant = ItemInventoryStock::where('itemInventory_id', $value['id'])
                        ->where('deleted_at', null)
                        ->where('stock', '>', 0)
                        ->orderBy('id', 'ASC')
                        ->get();

                foreach ($cant as  $item) {
                    if($item->stock >= $aux)
                    {
                        EgresDetailItemInventoryStock::create([
                            'egresDetail_id'=>$egresDetail->id,
                            'itemInventoryStock_id'=>$item->id,
                            'quantity'=>$aux
                        ]);
                        $item->decrement('stock', $aux);
                        $aux=0;                        
                    }
                    else
                    {                            
                        $aux = $aux-$item->stock;
                        EgresDetailItemInventoryStock::create([
                            'egresDetail_id'=>$egresDetail->id,
                            'itemInventoryStock_id'=>$item->id,
                            'quantity'=>$item->stock
                        ]);
                        $item->update([
                            'stock'=>0
                        ]);
                    }
                    if($aux == 0)
                    {
                        break;
                    }
                } 
            }

            DB::commit();
            return redirect()->route('egres-inventories.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('egres-inventories.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


    public function destroy($id)
    {
        $egres = EgresInventory::with(['egresInventoryDetails' => function($q){
                $q->where('deleted_at', null)
                    ->with(['egresDetailItemInventoryStock']);
            }])
            ->where('id',$id)
            ->first();
     
        DB::beginTransaction();
        try {        
            foreach ($egres->egresInventoryDetails as $detail) {
                foreach ($detail->egresDetailItemInventoryStock as $item) {
                    $itemInventory = ItemInventoryStock::where('id', $item->itemInventoryStock_id )->first();
                    $itemInventory->increment('stock', $item->quantity);
                }
            }
            $egres->delete();
            DB::commit();
            return redirect()->route('egres-inventories.index')->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('egres-inventories.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function printEgres($id)
    {
        $egres = EgresInventory::with(['egresInventoryDetails'=>function($q){
                $q->where('deleted_at', null)
                ->with(['itemInventory.category']);
            }])
            ->where('id', $id)
            ->first();

        return view('egres-inventories.print.print-egres', compact('egres'));
    }



}
