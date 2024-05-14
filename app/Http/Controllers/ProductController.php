<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{

    public function home(Category $category)
    {
        $products = Product::with('file')->where('category_id', $category->id)->get();
        return response()->json(['products' => $products], 200);
    }

    public function buscador(Request $request)
    {
        $buscador = $request->input('buscador');
        $category = $request->input('category');

        $products = Product::with('file')->where('category_id', $category["id"])->Where('name', 'like', '%' . $buscador . '%')->get();
        return response()->json(['products' => $products], 200);
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('Products.admin.index');
        } else {
            // $products = Product::query();
            $products = Product::with('file')->get();
            return DataTables::of($products)->toJson();
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        return response()->json(['info' => 'llegó'], 200);
    }

    public function show(Product $product)
    {
        $session = auth()->check();
        $product->load('file');
        return view('Products.client.show', compact('product', 'session'));
    }

    public function edit(Product $product)
    {
        //
    }

    public function update(Request $request, Product $product)
    {
        //
    }

    public function destroy(Product $product)
    {
        //
    }
}
