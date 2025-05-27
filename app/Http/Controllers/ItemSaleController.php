<?php

namespace App\Http\Controllers;

use App\Models\ItemSale;
use App\Models\ItemSaleStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemSaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->custom_authorize('browse_item_sales');
        return view('parameters.item-sales.browse');
    }

    public function list(){

        $this->custom_authorize('browse_item_sales');

        $search = request('search') ?? null;
        $paginate = request('paginate') ?? 10;

        $data = ItemSale::with(['category'])
                        ->where(function($query) use ($search){
                            $query->OrwhereHas('category', function($query) use($search){
                                $query->whereRaw($search ? "name like '%$search%'" : 1);
                            })
                            ->OrWhereRaw($search ? "id = '$search'" : 1)
                            ->OrWhereRaw($search ? "typeSale like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "name like '%$search%'" : 1);
                        })
                        ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);

        return view('parameters.item-sales.list', compact('data'));
    }

    public function show($id)
    {
        $this->custom_authorize('read_item_sales');

        $item = ItemSale::with(['category', 'itemSalestocks'=>function($q){
                $q->orderBy('id', 'DESC');
            }])
            ->where('id', $id)
            ->where('deleted_at', null)
            ->first();

        return view('parameters.item-sales.read', compact('item'));
    }

    public function storeStock(Request $request, $id)
    {
        $this->custom_authorize('add_item_sales');    
        DB::beginTransaction();
        try {
            ItemSaleStock::create([
                'itemSale_id' => $id,
                'quantity' =>  $request->quantity,
                'stock' => $request->quantity,
                'type' => 'Ingreso',
                'observation' => $request->observation,
            ]);
            DB::commit();
            return redirect()->route('voyager.item-sales.show', ['id'=>$id])->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('voyager.item-sales.show',  ['id'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        } 
    }

    public function destroyStock($id, $stock)
    {
        $item = ItemSaleStock::where('id', $stock)
                ->where('deleted_at', null)
                ->first();
        DB::beginTransaction();
        try {            
            $item->delete();
            DB::commit();
            return redirect()->route('voyager.item-sales.show', ['id'=>$id])->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('voyager.item-sales.show', ['id'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
