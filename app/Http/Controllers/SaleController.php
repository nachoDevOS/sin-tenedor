<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ItemSale;
use App\Models\ItemSaleStock;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleDetailItemSaleStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->custom_authorize('browse_sales');
        return view('sales.browse');
    }

    public function list(){

        $this->custom_authorize('browse_sales');

        $search = request('search') ?? null;
        $paginate = request('paginate') ?? 10;
        $status = request('status') ?? null;
        $typeSale = request('typeSale') ?? null;

        $data = Sale::with(['person','register', 'saleDetails'=>function($q){
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

        return view('sales.list', compact('data'));
    }

    public function show($id)
    {
        $sale = Sale::with(['person', 'register', 'saleDetails' => function($q){
                $q->where('deleted_at', null)
                ->with(['itemSale']);
            }])
            ->where('id',$id)
            ->first();

        return view('sales.read',compact('sale'));
    }

    public function create()
    {
        $this->custom_authorize('add_sales');
        $categories = Category::with(['itemSales' => function($query) {
                $query->where('deleted_at', null) // Solo productos en ventas activos
                    ->with(['itemSalestocks'=>function($q){
                        $q->where('deleted_at', null);
                    }]);
            }])->get();
        return view('sales.edit-add', compact('categories'));
    }

    public function ticket($typeSale)
    {
        $prefix = $typeSale == 'Mesa'? 'M':'L';
        $count = Sale::withTrashed()
                    ->where('typeSale', $typeSale)
                    ->whereDate('created_at', today())
                    ->count();

        return $prefix . '-' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        return $request;
        $this->custom_authorize('add_sales');
        if ($request->amountTotalSale > $request->amountReceived) {
            return redirect()->route('sales.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
        $ok = false;
        foreach ($request->products as $key => $value) {
            if ($value['typeSale'] == "Venta Con Stock") {
                $cant = ItemSaleStock::where('itemSale_id', $value['id'])
                        ->where('deleted_at', null)
                        ->where('stock', '>', 0)
                        ->get()->sum('stock');
                if($value['quantity'] > $cant)
                {
                    $ok=true;
                }
            }
        }
        if ($ok) {
            return redirect()->route('sales.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
        DB::beginTransaction();
        try {
            $sale = Sale::create([
                'typeSale'=>$request->typeSale,
                'ticket' => $this->ticket($request->typeSale),
                'person_id'=>$request->person_id??NULL,
                'amountReceived'=>$request->amountReceived,
                'amountChange'=>$request->amountReceived - $request->amountTotalSale,
                'dateSale'=>Carbon::now(),
                'amount'=>$request->amountTotalSale,
                'observation'=>$request->observation,
                'status'=>'Entregado'
            ]);

            foreach ($request->products as $key => $value) {
                $saleDetail = SaleDetail::create([
                    'sale_id'=>$sale->id,
                    'item_id'=>$value['id'],
                    'typeSaleItem'=>$value['typeSale'],
                    'price'=>$value['price'],
                    'quantity'=>$value['quantity'],
                    'amount'=>$value['quantity'] * $value['price']
                ]);

                if ($value['typeSale'] == "Venta Con Stock") 
                {
                    $aux = $value['quantity'];
                    $cant = ItemSaleStock::where('itemSale_id', $value['id'])
                            ->where('deleted_at', null)
                            ->where('stock', '>', 0)
                            ->orderBy('id', 'ASC')
                            ->get();

                    foreach ($cant as  $item) {
                        if($item->stock >= $aux)
                        {
                            SaleDetailItemSaleStock::create([
                                'saleDetail_id'=>$saleDetail->id,
                                'itemSaleStock_id'=>$item->id,
                                'quantity'=>$aux
                            ]);
                            $item->decrement('stock', $aux);
                            $aux=0;                        
                        }
                        else
                        {                            
                            $aux = $aux-$item->stock;
                            SaleDetailItemSaleStock::create([
                                'saleDetail_id'=>$saleDetail->id,
                                'itemSaleStock_id'=>$item->id,
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
            }

            DB::commit();
            return redirect()->route('sales.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return 0;
            return redirect()->route('sales.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }

    }


    public function destroy($id)
    {
        $sale = Sale::with(['saleDetails' => function($q){
                $q->where('deleted_at', null)
                    ->where('typeSaleItem', 'Venta Con Stock')
                    ->with(['saleDetailItemSaleStock']);
            }])
            ->where('id',$id)
            ->first();
     
        DB::beginTransaction();
        try {        
            foreach ($sale->saleDetails as $detail) {
                foreach ($detail->saleDetailItemSaleStock as $item) {
                    $itemSale = ItemSaleStock::where('id', $item->itemSaleStock_id)->first();
                    $itemSale->increment('stock', $item->quantity);
                }
            }
            $sale->delete();
            DB::commit();
            return redirect()->route('sales.index')->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('sales.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function saleSuccess($id)
    {
        $this->custom_authorize('add_sales');
        $sale=Sale::where('id', $id)->first();
        DB::beginTransaction();
        try {
            $sale->update([
                'status'=>'Entregado'
            ]);
            DB::commit();
            return redirect()->route('sales.index')->with(['message' => 'Entregado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return 0;
            return redirect()->route('sales.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


    public function printTicket($id)
    {
        $sale = Sale::with(['person', 'register', 'saleDetails' => function($q){
                $q->where('deleted_at', null)
                ->with(['itemSale']);
            }])
            ->where('id',$id)
            ->first();

        return view('sales.print.ticket',compact('sale'));
    }
    public function printComanda($id)
    {
        $sale = Sale::with([
                'person', 
                'register', 
                'saleDetails' => function($q) {
                    $q->where('deleted_at', null)
                    ->with(['itemSale.category']);
                }
            ])
            ->where('id', $id)
            ->first();
            
        return view('sales.print.comanda', compact('sale'));
    }
}
