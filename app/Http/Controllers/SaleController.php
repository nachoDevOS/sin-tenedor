<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ItemSale;
use Illuminate\Http\Request;

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

    public function create()
    {
        $categories = Category::with(['itemSales' => function($query) {
                $query->where('deleted_at', null) // Solo productos en ventas activos
                    ->with(['itemSalestocks'=>function($q){
                        $q->where('deleted_at', null);
                    }]);
            }])->get();
        return view('sales.edit-add', compact('categories'));
    }
}
