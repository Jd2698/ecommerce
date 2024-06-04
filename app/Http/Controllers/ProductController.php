<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Traits\UploadFile;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Requests\Product\ProductUpdateRequest;

class ProductController extends Controller
{
    use UploadFile;

    public function home(Category $category)
    {
        $products = Product::with('file')->where('category_id', $category->id)->where('stock', '>', '0')->get();
        return response()->json(['products' => $products], 200);
    }

    public function buscador(Request $request)
    {
        $buscador = $request->input('buscador');
        $category_id = $request->input('category');

        $category = Category::find($category_id);

        if (!$category) {
            $products = Product::with('file')->Where('name', 'like', '%' . $buscador . '%')->get();
            return response()->json(['products' => $products], 200);
        } else {
            $products = Product::with('file')->where('category_id', $category_id)->Where('name', 'like', '%' . $buscador . '%')->get();
            return response()->json(['products' => $products], 200);
        }
    }

    public function cart()
    {
        return view('Cart.index');
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('Products.admin.index');
        } else {
            // $products = Product::query();
            $products = Product::with('file', 'category')->get();
            return DataTables::of($products)->toJson();
        }
    }

    public function create()
    {
        //
    }

    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $product = new Product($request->all());
            $product->save();
            $this->uploadFile($product, $request);
            DB::commit();
            return response()->json([], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function show(Request $request, Product $product)
    {
        $product->load('file');
        if (!$request->ajax()) {
            return view('Products.client.show', compact('product'));
        } else {
            return response()->json(['product' => $product], 200);
        }
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        if (!$request->hasFile('file')) unset($request['file']);

        try {
            DB::beginTransaction();
            $product->update($request->all());

            $this->uploadFile($product, $request);
            DB::commit();
            return response()->json([], 204);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        $this->deleteFile($product);
        return response()->json([], 204);
    }
}
