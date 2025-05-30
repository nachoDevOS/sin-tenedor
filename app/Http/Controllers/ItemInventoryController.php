<?php

namespace App\Http\Controllers;

use App\Models\ItemInventory;
use Illuminate\Http\Request;

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
                            ->OrWhereRaw($search ? "typeSale like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "name like '%$search%'" : 1);
                        })
                        ->where('deleted_at', NULL)
                        ->whereRaw($category_id? "categoryInventory_id = '$category_id'" : 1)
                        ->orderBy('id', 'DESC')
                        ->paginate($paginate);

        return view('parameterInventories.item-inventories.list', compact('data'));
    }
}
