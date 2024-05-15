<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function home()
    {
        // $categories = Category::with('products')->get();
        $categories = Category::has('products')->with(['products' => function ($query) {
            $query->where('stock', '>', 0)->with('file');
        }])->get();
        return response()->json(['categories' => $categories], 200);
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories], 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Category $category)
    {
        return view('Products.client.index', compact('category'));
    }

    public function edit(Category $category)
    {
        //
    }

    public function update(Request $request, Category $category)
    {
        //
    }

    public function destroy(Category $category)
    {
        //
    }
}
