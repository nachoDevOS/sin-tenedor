<?php

namespace App\Http\Controllers;

use App\Models\EgresInventory;
use App\Models\ItemInventory;
use App\Models\ItemInventoryStock;
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
        // $this->custom_authorize('browse_egres_inventories');

            // $data = ItemInventoryStock::with(['itemInventory'])
            // ->where('deleted_at', null)
            // ->select('itemInventory_id', DB::raw("SUM(stock) as stock"), 'item_inventory.name')
            // ->groupBy('itemInventory_id')
            // ->get();

            $data = ItemInventoryStock::with(['itemInventory'])
    ->where('deleted_at', null)
    ->join('itemInventory', 'item_inventory.id', '=', 'item_inventory_stock.itemInventory_id')
    ->select('itemInventory_id', DB::raw("SUM(stock) as stock"), 'item_inventory.name')
    ->groupBy('itemInventory_id', 'item_inventory.name')
    ->get();
            // dump($data);

            // $data = ItemInventoryStock::with(['itemInventory'])          
            // ->where('deleted_at', null)
            // ->get(); 
            // dump($data);

        


        // $data = ItemInventoryStock::all();
        return $data;




        return view('egres-inventories.browse');
    }

    public function list(){
        $this->custom_authorize('browse_egres_inventories');

        $search = request('search') ?? null;
        $paginate = request('paginate') ?? 10;
        $status = request('status') ?? null;
        $typeSale = request('typeSale') ?? null;

        $data = EgresInventory::with(['person','register', 'saleDetails'=>function($q){
                            $q->where('deleted_at', null);
                        }])
                        ->where(function($query) use ($search){
                            $query->OrWhereRaw($search ? "id = '$search'" : 1)
                            ->OrWhereRaw($search ? "code like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "ticket like '%$search%'" : 1);
                        })
                        ->where('deleted_at', NULL)
                        ->whereRaw($typeSale? "typeSale = '$typeSale'" : 1)
                        ->whereRaw($status? "status = '$status'" : 1)
                        ->orderBy('id', 'DESC')
                        ->paginate($paginate);

        return view('egres-inventories.list', compact('data'));
    }

    public function create()
    {
        return view('egres-inventories.edit-add');
    }

    public function stockInventory(){
        $search = request('q');

        $data = ItemInventoryStock::with(['itemInventory'])
            ->Where(function($query) use ($search){
                if($search){
                    $query->OrwhereHas('itemInventory', function($query) use($search){
                        $query->whereRaw($search ? 'name like "%'.$search.'%"' : 1);
                    })
                    ->OrWhereRaw($search ? "id like '%$search%'" : 1);
                }
            })
            

            ->where('stock', '>', 0)
            ->where('deleted_at', null)

            ->select('itemInventory_id', DB::raw("SUM(stock) as stock"))
            ->groupBy('itemInventory_id')

            ->get(); 
            
    
        return response()->json($data);
    }



}
