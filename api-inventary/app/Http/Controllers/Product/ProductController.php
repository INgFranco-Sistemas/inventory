<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Models\Config\Sucursale;
use App\Models\Config\Warehouse;
use App\Models\Config\Unit;
use App\Models\Config\ProductCategorie;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get("search");

        $products = Product::where(DB::raw("products.title || '' || products.sku"),"ilike","%".$search."%")->orderBy("id","desc")->paginate(25);

        return response()->json([
            "total" => $products->total(),
            "total_page" => $products->lastPage(),
            "products" => ProductCollection::make($products),
        ]);
    }

    public function config() 
    {
        $sucursales = Sucursale::where("state",1)->get();
        $warehouses = Warehouse::where("state",1)->get();
        $units = Unit::where("state",1)->get();
        $categories = ProductCategorie::where("state",1)->get();

        return response()->json([
            "sucursales" => $sucursales->map(function($sucursale) {
                return [
                    "id" => $sucursale->id,
                    "name" => $sucursale->name
                ];
            }),
            "warehouses" => $warehouses->map(function($warehouse) {
                return [
                    "id" => $warehouse->id,
                    "name" => $warehouse->name
                ];
            }),
            "units" => $units->map(function($unit) {
                return [
                    "id" => $unit->id,
                    "name" => $unit->name
                ];
            }),
            "categories" => $categories->map(function($categorie) {
                return [
                    "id" => $categorie->id,
                    "name" => $categorie->title,
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $is_product_exists = Product::where("title",$request->title)->first();
        if($is_product_exists){
            return response()->json([
                "message" => 403,
                "message_text" => "EL NOMBRE DEL PRODUCTO YA EXISTE"
            ]);
        }

        $is_product_sku_exists = Product::where("sku",$request->title)->first();
        if($is_product_sku_exists){
            return response()->json([
                "message" => 403,
                "message_text" => "EL SKU DEL PRODUCTO YA EXISTE"
            ]);
        }

        if($request->hasFile("image")){
            // $path = Storage::putFile("categories",$request->file("image"));
            // $request->request->add(["imagen" => $path]);
        }

        $product = Product::create($request->all());

        return response()->json([
            "message" => 200,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            "product" => ProductResource::make($product),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $is_product_exists = Product::where("title",$request->title)->where("id",$id)->first();
        if($is_product_exists){
            return response()->json([
                "message" => 403,
                "message_text" => "EL NOMBRE DEL PRODUCTO YA EXISTE"
            ]);
        }

        $is_product_sku_exists = Product::where("sku",$request->title)->where("id",$id)->first();
        if($is_product_sku_exists){
            return response()->json([
                "message" => 403,
                "message_text" => "EL SKU DEL PRODUCTO YA EXISTE"
            ]);
        }

        if($request->hasFile("image")){
            // $path = Storage::putFile("categories",$request->file("image"));
            // $request->request->add(["imagen" => $path]);
        }

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json([
            "message" => 200,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            "message" => 200,
        ]);
    }
}
