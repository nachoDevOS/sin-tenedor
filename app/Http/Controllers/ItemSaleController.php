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
        
        $category = ItemSale::with(['category'])
            ->where('deleted_at', null)
            ->where('status', 1)
            ->select('category_id')
            ->groupBy('category_id')
            ->get();

        // return $category;

        return view('parameters.item-sales.browse', compact('category'));
    }

    public function list(){

        $this->custom_authorize('browse_item_sales');

        $search = request('search') ?? null;
        $paginate = request('paginate') ?? 10;
        $category_id = request('category') ?? null;

        $data = ItemSale::with(['category', 'itemSalestocks'=>function($q){
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
                        ->whereRaw($category_id? "category_id = '$category_id'" : 1)
                        ->orderBy('id', 'DESC')
                        ->paginate($paginate);

        return view('parameters.item-sales.list', compact('data'));
    }

    public function store(Request $request)
    {
        $this->custom_authorize('add_item_sales');
        $request->validate([
            'image' => 'image|mimes:jpeg,jpg,png,bmp,webp'
        ]);
        try {
            // Si envian las imágenes
            $storageController = new StorageController();

            // $images = [];
            // if ($request->images) {
            //     $images = json_decode($request->images);
            //     foreach ($request->images as $image) {
            //         $image_store = $this->store_image($image, 'posts');
            //         if($image_store){
            //             array_push($images, $image_store);
            //         }
            //     }
            // }

            ItemSale::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'price' => $request->price,
                'typeSale' => $request->typeSale,
                'observation' => $request->observation,
                'image' => $storageController->store_image($request->image, 'item-sales'),
                // 'images' => json_encode($images),
            ]);

            DB::commit();
            return redirect()->route('voyager.item-sales.index')->with(['message' => 'Registrado exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('voyager.item-sales.index')->with(['message' => $th->getMessage(), 'alert-type' => 'error']);
        }
    }


    public function update(Request $request, $id){
        $this->custom_authorize('edit_item_sales');
        $request->validate([
            'image' => 'image|mimes:jpeg,jpg,png,bmp,webp'
        ]);

        DB::beginTransaction();
        try {
            $storageController = new StorageController();
            
            $itemSale = ItemSale::find($id);
            $itemSale->category_id = $request->category_id;
            $itemSale->name = $request->name;
            $itemSale->price = $request->price;
            $itemSale->typeSale = $request->typeSale;
            $itemSale->observation = $request->observation;
            $itemSale->status = $request->status=='on' ? 1 : 0;

            // Si envian el banner
            if ($request->image) {
                $itemSale->image = $storageController->store_image($request->image, 'item-sales');
            }
            // return $itemSale;

            // Si envian las imágenes
            // if ($request->images) {
            //     $images = $post->images ? json_decode($post->images) : [];
            //     foreach ($request->images as $image) {
            //         $image_store = $this->store_image($image, 'posts');
            //         if($image_store){
            //             array_push($images, $image_store);
            //         }
            //     }
            //     $post->images = json_encode($images);
            // }
            
            $itemSale->update();

            DB::commit();
            return redirect()->route('voyager.item-sales.index')->with(['message' => 'Actualizada exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('voyager.item-sales.index')->with(['message' => $th->getMessage(), 'alert-type' => 'error']);
        }
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
