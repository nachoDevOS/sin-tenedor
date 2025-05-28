<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ItemSale;
use App\Models\Sale;
use App\Models\SaleDetail;
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
                        // ->where(function($query) use ($search){
                        //     $query->OrwhereHas('category', function($query) use($search){
                        //         $query->whereRaw($search ? "name like '%$search%'" : 1);
                        //     })
                        //     ->OrWhereRaw($search ? "id = '$search'" : 1)
                        //     ->OrWhereRaw($search ? "typeSale like '%$search%'" : 1)
                        //     ->OrWhereRaw($search ? "name like '%$search%'" : 1);
                        // })
                        ->where('deleted_at', NULL)
                        ->whereRaw($typeSale? "typeSale = '$typeSale'" : 1)
                        ->whereRaw($status? "status = '$status'" : 1)
                        ->orderBy('id', 'DESC')
                        ->paginate($paginate);

        return view('sales.list', compact('data'));
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
        // return $request;
        if ($request->amountTotalSale > $request->amountReceived) {
            return redirect()->route('sales.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }

        $this->custom_authorize('add_sales');
        DB::beginTransaction();
        try {


            // return $this->ticket($request->typeSale);


            $sale = Sale::create([
                'typeSale'=>$request->typeSale,
                'ticket' => $this->ticket($request->typeSale),
                'person_id'=>$request->person_id??NULL,
                'amountReceived'=>$request->amountReceived,
                'amountChange'=>$request->amountReceived - $request->amountTotalSale,
                'dateSale'=>Carbon::now(),
                'amount'=>$request->amountTotalSale,
            ]);

            foreach ($request->products as $key => $value) {
                // dump($value['name']);
                $saleDetail = SaleDetail::create([
                    'sale_id'=>$sale->id,
                    'item_id'=>$value['id'],
                    'typeSaleItem'=>$value['typeSale'],
                    'price'=>$value['price'],
                    'quantity'=>$value['quantity'],
                    'amount'=>$value['quantity'] * $value['price']
                ]);
            }

            // return 1;


            

            DB::commit();
            return redirect()->route('sales.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
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

    // public function printComanda($id)
    // {
    //     $sale = Sale::with(['person', 'register', 'saleDetails' => function($q){
    //             $q->where('deleted_at', null)
    //             ->with(['itemSale.category']);
    //         }])
    //         ->where('id',$id)
    //         ->first();
    //     return view('sales.print.ticket',compact('sale'));
    // }

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
