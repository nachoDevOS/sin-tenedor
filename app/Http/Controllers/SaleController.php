<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use App\Models\Category;
use App\Models\ItemSale;
use App\Models\ItemSaleStock;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleDetailItemSaleStock;
use App\Models\SaleTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Traits\Loggable;


class SaleController extends Controller
{
    use Loggable;

    public $storageController;
    public function __construct()
    {
        $this->middleware('auth');
        $this->storageController = new StorageController();
    }

    public function index()
    {
        $this->custom_authorize('browse_sales');
        return view('sales.browse');
    }

    public function list()
    {
        $this->custom_authorize('browse_sales');

        $search = request('search') ?? null;
        $paginate = request('paginate') ?? 10;
        $status = request('status') ?? null;
        $typeSale = request('typeSale') ?? null;

        $data = Sale::with([
            'person',
            'register',
            'saleDetails' => function ($q) {
                $q->where('deleted_at', null);
            },
        ])
            ->where(function ($query) use ($search) {
                $query
                    ->OrWhereRaw($search ? "id = '$search'" : 1)
                    ->OrWhereRaw($search ? "code like '%$search%'" : 1)
                    ->OrWhereRaw($search ? "ticket like '%$search%'" : 1);
            })
            ->where('deleted_at', null)
            ->whereRaw($typeSale ? "typeSale = '$typeSale'" : 1)
            ->whereRaw($status ? "status = '$status'" : 1)
            ->orderBy('id', 'DESC')
            ->paginate($paginate);

        return view('sales.list', compact('data'));
    }

    public function show($id)
    {
        $sale = Sale::with([
            'person',
            'register',
            'saleTransactions',
            'saleDetails' => function ($q) {
                $q->where('deleted_at', null)->with(['itemSale']);
            },
        ])
            ->where('id', $id)
            ->first();

        return view('sales.read', compact('sale'));
    }

    public function create()
    {
        $this->custom_authorize('add_sales');
        $categories = Category::with([
            'itemSales' => function ($query) {
                $query
                    ->where('deleted_at', null) // Solo productos en ventas activos
                    ->with([
                        'itemSalestocks' => function ($q) {
                            $q->where('deleted_at', null);
                        },
                    ]);
            },
        ])->get();

        $cashier = $this->cashier('user', Auth::user()->id, 'status = "abierta"');

        return view('sales.edit-add', compact('categories', 'cashier'));
    }

    public function ticket($typeSale)
    {
        $prefix = $typeSale == 'Mesa' ? 'M' : 'L';
        $count = Sale::withTrashed()->where('typeSale', $typeSale)->whereDate('created_at', today())->count();

        return $prefix . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $amountReceivedEfectivo = $request->amountReceivedEfectivo ? $request->amountReceivedEfectivo : 0;
        $amountReceivedQr = $request->amountReceivedQr ? $request->amountReceivedQr : 0;

        $this->custom_authorize('add_sales');
        if ($request->amountTotalSale > $amountReceivedEfectivo + $amountReceivedQr) {
            return redirect()
                ->route('sales.create')
                ->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }

        $cashier = $this->cashier('user', Auth::user()->id, 'status = "abierta"');

        if (!$cashier) {
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'Usted no cuenta con caja abierta.', 'alert-type' => 'warning']);
        }

        $ok = false;
        foreach ($request->products as $key => $value) {
            if ($value['typeSale'] == 'Venta Con Stock') {
                $cant = ItemSaleStock::where('itemSale_id', $value['id'])->where('deleted_at', null)->where('stock', '>', 0)->get()->sum('stock');
                if ($value['quantity'] > $cant) {
                    $ok = true;
                }
            }
        }
        if ($ok) {
            return redirect()
                ->route('sales.create')
                ->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'status' => 'Completado',
            ]);
            $sale = Sale::create([
                'typeSale' => $request->typeSale,
                'ticket' => $this->ticket($request->typeSale),
                'person_id' => $request->person_id ?? null,
                'cashier_id' => $cashier->id,

                'amountReceived' => $amountReceivedQr + $amountReceivedEfectivo,

                'amountChange' => $amountReceivedEfectivo + $amountReceivedQr - $request->amountTotalSale,
                'dateSale' => Carbon::now(),
                'amount' => $request->amountTotalSale,
                'observation' => $request->observation,
                'status' => 'Entregado',
            ]);
            // return $request;
            if ($request->paymentType == 'Efectivo' || $request->paymentType == 'Ambos') {
                SaleTransaction::create([
                    'sale_id' => $sale->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $request->amountTotalSale - $amountReceivedQr,
                    'paymentType' => 'Efectivo',
                ]);
            }
            if ($request->paymentType == 'Qr' || $request->paymentType == 'Ambos') {
                SaleTransaction::create([
                    'sale_id' => $sale->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $amountReceivedQr,
                    'paymentType' => 'Qr',
                ]);
            }

            foreach ($request->products as $key => $value) {
                $saleDetail = SaleDetail::create([
                    'sale_id' => $sale->id,
                    'item_id' => $value['id'],
                    'typeSaleItem' => $value['typeSale'],
                    'price' => $value['price'],
                    'quantity' => $value['quantity'],
                    'amount' => $value['quantity'] * $value['price'],
                ]);

                if ($value['typeSale'] == 'Venta Con Stock') {
                    $aux = $value['quantity'];
                    $cant = ItemSaleStock::where('itemSale_id', $value['id'])->where('deleted_at', null)->where('stock', '>', 0)->orderBy('id', 'ASC')->get();

                    foreach ($cant as $item) {
                        if ($item->stock >= $aux) {
                            SaleDetailItemSaleStock::create([
                                'saleDetail_id' => $saleDetail->id,
                                'itemSaleStock_id' => $item->id,
                                'quantity' => $aux,
                            ]);
                            $item->decrement('stock', $aux);
                            $aux = 0;
                        } else {
                            $aux = $aux - $item->stock;
                            SaleDetailItemSaleStock::create([
                                'saleDetail_id' => $saleDetail->id,
                                'itemSaleStock_id' => $item->id,
                                'quantity' => $item->stock,
                            ]);
                            $item->update([
                                'stock' => 0,
                            ]);
                        }
                        if ($aux == 0) {
                            break;
                        }
                    }
                }
            }

            DB::commit();
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success', 'sale_id' => $sale->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return 0;
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function destroy($id)
    {
        $sale = Sale::with([
            'saleDetails' => function ($q) {
                $q->where('deleted_at', null)
                    ->where('typeSaleItem', 'Venta Con Stock')
                    ->with(['saleDetailItemSaleStock']);
            },
        ])
            ->where('id', $id)
            ->first();
        $cashier = Cashier::where('status', 'abierta')->where('id', $sale->cashier_id)->first();
        if (!$cashier) {
            return redirect()
                ->back()
                ->with(['message' => 'La caja se encuentra cerrada..', 'alert-type' => 'error']);
        }

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
            // return redirect()->route('sales.index')->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
            return redirect()
                ->back()
                ->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function saleSuccess($id)
    {
        $this->custom_authorize('add_sales');
        $sale = Sale::where('id', $id)->first();
        DB::beginTransaction();
        try {
            $sale->update([
                'status' => 'Entregado',
            ]);
            DB::commit();
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'Entregado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return 0;
            return redirect()
                ->route('sales.index')
                ->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function printTicket($id)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::with([
                'person',
                'register',
                'saleDetails' => function ($q) {
                    $q->where('deleted_at', null)->with(['itemSale']);
                },
            ])
                ->where('id', $id)
                ->first();

            $array = [];
            foreach ($sale->saleDetails as $item) {
                array_push($array, [
                    // 'quantity' => $item->quantity,
                    'quantity' => (float)$item->quantity == (int)$item->quantity 
                        ? (int)$item->quantity 
                        : (float)$item->quantity,
                    'product' => $item->itemSale->name,
                    'total' => $item->amount,
                ]);
            }

            $data = [
                'template' => 'ticket', // Asegúrate de usar 'template' en lugar de 'templeate'
                'sale_number' => $sale->ticket,
                'sale_type' => $sale->typeSale,
                'details' => $array,
            ];


            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post('http://localhost:3010/print', $data);


            DB::commit();
            return view('sales.print.ticket', compact('sale'));
        } catch (\Throwable $th) {
            //throw $th
            DB::rollBack();
            // $this->logError($th, $id);
            log($th->getMessage());

            return 0;

        }
        
    }

    // function checkServiceStatus($url) {

    //     $parsedUrl = parse_url($url);

    //     $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];

    //     if (isset($parsedUrl['port'])) {
    //         $baseUrl .= ':' . $parsedUrl['port'];
    //     }

    //     $context = stream_context_create([
    //         'http' => ['timeout' => 3] // tiempo de espera de 3 segundos para verificar 127.0.0.1:port
    //     ]);

    //     $response = @file_get_contents($baseUrl, false, $context);

    //     return ($response !== false) ? true : false;
    // }

    public function printComanda($id)
    {
        $sale = Sale::with([
            'person',
            'register',
            'saleDetails' => function ($q) {
                $q->where('deleted_at', null)->with(['itemSale.category']);
            },
        ])
            ->where('id', $id)
            ->first();

        // Agrupar automáticamente por categoría sin orden específico
        $groupedItems = $sale->saleDetails->groupBy(function ($item) {
            return optional($item->itemSale->category)->name ?? 'Otros';
        });

        return view('sales.print.comanda', compact('sale', 'groupedItems'));
    }

    public function fullPrint($id)
    {
        $sale = Sale::with([
            'person',
            'register',
            'saleDetails' => function ($q) {
                $q->where('deleted_at', null)->with(['itemSale.category']);
            },
        ])
            ->where('id', $id)
            ->first();

        // Agrupar automáticamente por categoría sin orden específico
        $groupedItems = $sale->saleDetails->groupBy(function ($item) {
            return optional($item->itemSale->category)->name ?? 'Otros';
        });

        return view('sales.print.fullPrint', compact('sale', 'groupedItems'));
    }
}
