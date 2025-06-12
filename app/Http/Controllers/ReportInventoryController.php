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


    // ############################################################ SALIDAS #########################################################

    public function indexInventoryEgres()
    {
        $this->custom_authorize('browse_reportinventories-egres');
        return view('reports.inventories.egres.report');
    }

    public function listSale(Request $request)
    {
        $detail = $request->detail;
        $start = $request->start;
        $finish = $request->finish;
        $sales = Sale::with(['person', 'register','saleDetails'=>function($q){
                $q->where('deleted_at', null)
                ->with(['itemSale.category']);
            }])
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $finish)
            ->where('status', 'Entregado')
            ->where('deleted_at', null)
            ->orderBy('created_at', 'ASC')
            ->get();

        // return 1;
        
        if($request->print){
            return view('reports.sales.sales.print', compact('sales', 'detail', 'start', 'finish'));
        }else{
            return view('reports.sales.sales.list', compact('sales', 'detail'));
        }
    }



    // ########################################################### STOCK DISPONIBLE ##################################################
    public function indexInventoryStock()
    {
        $this->custom_authorize('browse_reportinventories-stock');
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
        $this->custom_authorize('browse_reportinventories-income');

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
