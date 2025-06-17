<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ItemSale;
use App\Models\ItemSaleStock;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Luecano\NumeroALetras\NumeroALetras;
use Illuminate\Support\Facades\DB;

class ReportSaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }



    // ############################################################ VENTAS #########################################################

    public function indexSale()
    {
        $this->custom_authorize('browse_reportsales');       

        return view('reports.sales.sales.report');
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
        
        if($request->print){
            return view('reports.sales.sales.print', compact('sales', 'detail', 'start', 'finish'));
        }else{
            return view('reports.sales.sales.list', compact('sales', 'detail'));
        }
    }




    // ########################################################### STOCK DISPONIBLE ##################################################
    public function indexSaleStock()
    {
        $this->custom_authorize('browse_reportsales-stock');
        $category = ItemSale::with(['category'])
            ->where('deleted_at', null)
            ->where('status', 1)
            ->select('category_id')
            ->groupBy('category_id')
            ->get();
        return view('reports.sales.availableStock.report', compact('category'));
    }

    public function listSaleStock(Request $request)
    {
        $query = ItemSale::with(['category'])
            ->where('deleted_at', null)
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->where($request->category_id ? ['category_id' => $request->category_id] : []);

        // Lógica para filtrar según typeSale
        if ($request->typeSale == "Venta Con Stock") {
            // Solo productos con stock > 0 Y typeSale = "Venta Con Stock"
            $query->where('typeSale', 'Venta Con Stock')
                ->whereHas('itemSalestocks', function($query) {
                    $query->where('stock', '>', 0)->where('deleted_at', null);
                });
        } elseif ($request->typeSale == "Venta Sin Stock") {
            // Solo productos typeSale = "Venta Sin Stock" (sin importar stock)
            $query->where('typeSale', 'Venta Sin Stock');
        } else {
            // "Todos" (mostrar productos según su tipo configurado):
            // - Si es "Venta Con Stock", debe tener stock > 0.
            // - Si es "Venta Sin Stock", se muestra sin restricción.
            $query->where(function($subQuery) {
                $subQuery->where('typeSale', 'Venta Sin Stock')
                    ->orWhere(function($q) {
                        $q->where('typeSale', 'Venta Con Stock')
                            ->whereHas('itemSalestocks', function($q) {
                                $q->where('stock', '>', 0)->where('deleted_at', null);
                            });
                    });
            });
        }

        // Cargamos la relación y calculamos el stock (para todos los casos)
        $query->with(['itemSalestocks'])
            ->withSum('itemSalestocks as total_stock', 'stock');

        $data = $query->get();

        if($request->print){
            return view('reports.sales.availableStock.print', compact('data'));
        }else{
            return view('reports.sales.availableStock.list', compact('data'));
        }
    }

    // ############################################# INCOME ##############################################################
    public function indexSaleIncome()
    {
        $this->custom_authorize('browse_reportsales-income');
        return view('reports.sales.income.report');
    }

    public function listSaleIncome(Request $request)
    {
        $start = $request->start;
        $finish = $request->finish;
        $data = ItemSaleStock::with('itemSale.category', 'register')
            ->where('deleted_at', null)
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $finish)
            ->orderBy('created_at', 'ASC')
            ->get();

        if($request->print){
            return view('reports.sales.income.print', compact('data',  'start', 'finish'));
        }else{
            return view('reports.sales.income.list', compact('data'));
        }
    }
}
