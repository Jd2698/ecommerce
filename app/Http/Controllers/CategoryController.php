<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{

    public function home()
    {
        $categories = Category::has('products')->with(['products' => function ($query) {
            $query->where('stock', '>', 0)->with('file');
        }])->get();
        return response()->json(['categories' => $categories], 200);
    }

    public function homeShowProducts(Category $category)
    {
        return view('Products.client.index', compact('category'));
    }

    //private

    public function getAll()
    {
        $categories = Category::all();
        return DataTables::of($categories)->toJson();
    }

    public function index(Request $request)
    {
        return view('Categories.index');
    }

    public function store(CategoryRequest $request)
    {
        $category = new Category($request->all());
        $category->save();
        return response()->json([], 204);
    }

    public function show(Category $category)
    {
        return response()->json(['category' => $category], 200);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->all());
        return response()->json([], 204);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([], 204);
    }
}
