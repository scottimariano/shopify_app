<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;
use PHPShopify\ShopifySDK;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $shopify = app(ShopifySDK::class);
        $products = $shopify->Product->get();
        
        return $products;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $productInfo = [
            "title"         => $request->input('title'),
            "body_html"     => $request->input('body_html', "<strong>No description available</strong>"),
            "vendor"        => $request->input('vendor'),
            "product_type"  => $request->input('product_type'),
        ];
        
        $shopify = app(ShopifySDK::class);

        try {
            $product = $shopify->Product->post($productInfo);
            return response()->json($product);
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
