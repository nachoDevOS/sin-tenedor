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
        $category = ItemSale::with(['category'])
            ->where('deleted_at', null)
            ->select('category_id')
            ->groupBy('category_id')
            ->get();



        $categories = Category::with(['itemSales' => function($query) {
                $query->where('deleted_at', null); // Solo productos activos
            }])->get();
        return view('sales.edit-add', compact('categories'));
    }
}
