<?php

namespace App\Http\Controllers;

use App\Models\ItemInventoryStock;
use Illuminate\Http\Request;

class ReportInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
