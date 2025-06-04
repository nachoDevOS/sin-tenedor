<?php

namespace App\Http\Controllers;

use App\Models\ItemInventory;
use App\Models\ItemInventoryStock;
use Illuminate\Http\Request;

class ReportInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    // ########################################################### STOCK DISPONIBLE ##################################################
    public function indexInventoryStock()
    {
        $category = ItemInventory::with(['category'])
            ->where('deleted_at', null)
            ->where('status', 1)
            ->select('categoryInventory_id')
            ->groupBy('categoryInventory_id')
            ->get();
        // return $category;
        return view('reports.inventories.stock.report', compact('category'));
    }

    public function listInventoryStock(Request $request)
    {
        $data = ItemInventory::with('category', 'itemInventoryStocks')
            ->where('deleted_at', null)
            ->where('status', 1)
            ->where($request->category_id ? ['categoryInventory_id' => $request->category_id] : [])
            ->where($request->dispensingType ? ['dispensingType' => $request->dispensingType] : [])
            ->whereHas('itemInventoryStocks', function($q) {
                $q->where('deleted_at', null)
                  ->where('stock', '>', 0);
            })
            ->withSum(['itemInventoryStocks as total_stock' => function($q) {
                $q->where('deleted_at', null)
                  ->where('stock', '>', 0);
            }], 'stock')
            ->orderBy('name', 'ASC')
            ->get();

        if($request->print){
            return view('reports.inventories.stock.print', compact('data'));
        }else{
            return view('reports.inventories.stock.list', compact('data'));
        }
    }

    // Ingresos al almacen
    public function indexInventoryIncome()
    {
        return view('reports.inventories.income.report');
    }
    public function listInventoryIncome(Request $request)
    {
        $start = $request->start;
        $finish = $request->finish;
        $data = ItemInventoryStock::with('itemInventory.category', 'register')
            ->where('deleted_at', null)
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $finish)
            ->orderBy('created_at', 'ASC')
            ->get();

        if($request->print){
            return view('reports.inventories.income.print', compact('data',  'start', 'finish'));
        }else{
            return view('reports.inventories.income.list', compact('data'));
        }
    }
}
