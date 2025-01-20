<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use PHPShopify\ShopifySDK;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $shopify = app(ShopifySDK::class);
    
        $products = Cache::remember('shopify_products_index', 20, function () use ($shopify) {
            return $shopify->Product->get();
        });
    
        return $products;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            
            $productInfo = [
                "title"         => $request->input('title'),
                "description"   => $request->input('description', "<strong>No description available</strong>"),
                "vendor"        => $request->input('vendor'),
                "product_type"  => $request->input('product_type'),
            ];
                    
            $shopify = app(ShopifySDK::class);
            $shopifyProductInfo = $shopify->Product->post($productInfo);
            $productInfo['shopify_id'] = $shopifyProductInfo['id'];
            $product = Product::create($productInfo);
            $product->save();
            return response()->json($product, 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el producto'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shopify = app(ShopifySDK::class);
        $product = $shopify->Product($id)->get();
        
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::where('shopify_id', $id)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $productInfo = [
            "title"         => $request->input('title'),
            "description"   => $request->input('description'),
            "vendor"        => $request->input('vendor'),
            "product_type"  => $request->input('product_type'),
        ];

        $product->update($productInfo);
        $product->save();

        $shopify = app(ShopifySDK::class);
        $shopify->Product($id)->put($productInfo);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shopify = app(ShopifySDK::class);
        $shopify->Product($id)->delete();

        Product::where('shopify_id', $id)->delete();

        return response()->json("Product deleted OK", 204);
    }
}
