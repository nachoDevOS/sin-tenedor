<?php

namespace App\Http\Controllers;

use App\Models\ItemInventory;
use App\Models\ItemInventoryStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->custom_authorize('browse_item_inventories');
        
        $category = ItemInventory::with(['category'])
            ->where('deleted_at', null)
            ->select('categoryInventory_id')
            ->groupBy('categoryInventory_id')
            ->get();
        return view('parameterInventories.item-inventories.browse', compact('category'));
    }

    public function list(){
        $this->custom_authorize('browse_item_inventories');
        $search = request('search') ?? null;
        $paginate = request('paginate') ?? 10;
        $category_id = request('category') ?? null;

        $data = ItemInventory::with(['category', 'itemInventoryStocks'=>function($q){
                            $q->where('deleted_at', null);
                        }])
                        ->where(function($query) use ($search){
                            $query->OrwhereHas('category', function($query) use($search){
                                $query->whereRaw($search ? "name like '%$search%'" : 1);
                            })
                            ->OrWhereRaw($search ? "id = '$search'" : 1)
                            ->OrWhereRaw($search ? "observation like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "name like '%$search%'" : 1);
                        })
                        ->where('deleted_at', NULL)
                        ->whereRaw($category_id? "categoryInventory_id = '$category_id'" : 1)
                        ->orderBy('id', 'DESC')
                        ->paginate($paginate);

        return view('parameterInventories.item-inventories.list', compact('data'));
    }


    public function store(Request $request)
    {
        $this->custom_authorize('browse_item_inventories');
        $request->validate([
            'image' => 'image|mimes:jpeg,jpg,png,bmp,webp'
        ]);
        try {
            $storageController = new StorageController();

            ItemInventory::create([
                'categoryInventory_id' => $request->categoryInventory_id,
                'name' => $request->name,
                'dispensingType' => $request->dispensingType,
                'observation' => $request->observation,
                'image' => $storageController->store_image($request->image, 'item-inventories'),
            ]);

            DB::commit();
            return redirect()->route('voyager.item-inventories.index')->with(['message' => 'Registrado exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('voyager.item-inventories.index')->with(['message' => $th->getMessage(), 'alert-type' => 'error']);
        }
    }


    public function update(Request $request, $id){
        $this->custom_authorize('browse_item_inventories');
        $request->validate([
            'image' => 'image|mimes:jpeg,jpg,png,bmp,webp'
        ]);
        DB::beginTransaction();
        try {
            $storageController = new StorageController();
            
            $itemInventory = ItemInventory::find($id);
            $itemInventory->categoryInventory_id = $request->categoryInventory_id;
            $itemInventory->name = $request->name;
            $itemInventory->dispensingType = $request->dispensingType;
            $itemInventory->observation = $request->observation;
            $itemInventory->status = $request->status=='on' ? 1 : 0;

            if ($request->image) {
                $itemInventory->image = $storageController->store_image($request->image, 'item-inventories');
            }
            
            $itemInventory->update();

            DB::commit();
            return redirect()->route('voyager.item-inventories.index')->with(['message' => 'Actualizada exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('voyager.item-inventories.index')->with(['message' => $th->getMessage(), 'alert-type' => 'error']);
        }
    }


    public function show($id)
    {
        $this->custom_authorize('read_item_inventories');
        $item = ItemInventory::with(['category', 'itemInventoryStocks'=>function($q){
                $q->orderBy('id', 'DESC');
            }])
            ->where('id', $id)
            ->where('deleted_at', null)
            ->first();
        return view('parameterInventories.item-inventories.read', compact('item'));
    }

    public function storeStock(Request $request, $id)
    {
        $this->custom_authorize('add_item_inventories');    
        // return $id;
        DB::beginTransaction();
        try {
            ItemInventoryStock::create([
                'itemInventory_id' => $id,
                'quantity' =>  $request->quantity,
                'stock' => $request->quantity,
                'type' => 'Ingreso',
                'observation' => $request->observation,
            ]);
            DB::commit();
            return redirect()->route('voyager.item-inventories.show', ['id'=>$id])->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('voyager.item-inventories.show',  ['id'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        } 
    }

    public function destroyStock($id, $stock)
    {
        $item = ItemInventoryStock::where('id', $stock)
                ->where('deleted_at', null)
                ->first();
        DB::beginTransaction();
        try {            
            $item->delete();
            DB::commit();
            return redirect()->route('voyager.item-inventories.show', ['id'=>$id])->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('voyager.item-inventories.show', ['id'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
