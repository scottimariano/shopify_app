<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPShopify\ShopifySDK;


class InventoryController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shopify = app(ShopifySDK::class);
        $product = $shopify->Product($id)->get();
        return $this->inventoryProcess($product);
    }

    private function inventoryProcess($product){
        $inventory = ['productId' => $product['id'], 'total_inventory' => 0];
        foreach ($product['variants'] as $key => $variant) {
            $inventory['total_inventory'] += $variant['inventory_quantity'];
            $inventory[$variant['id']] = $variant['inventory_quantity'];
        }

        return $inventory;
    }
}
