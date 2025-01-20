<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderData;

    public function __construct(array $orderData)
    {
        $this->orderData = $orderData;
    }

    public function handle()
    {
        $order = Order::create([
            'shopify_id' => $this->orderData['id'],
            'status' => $this->orderData['fulfillment_status'] ?? null,
            'financial_status' => $this->orderData['financial_status'] ?? null,
            'subtotal_price' => $this->orderData['subtotal_price'],
            'total_price' => $this->orderData['total_price'],
            'currency' => $this->orderData['currency'],
            'email' => $this->orderData['email'],
            'created_at_shopify' => $this->orderData['created_at'],
            'updated_at_shopify' => $this->orderData['updated_at'],
        ]);

        foreach ($this->orderData['line_items'] as $item) {
            $product = Product::firstOrCreate(
                ['shopify_id' => $item['product_id']],
                [
                    'title' => $item['name'],
                    'price' => $item['price'],
                    'sku' => $item['sku'] ?? null,
                ]
            );

            $order->products()->attach($product, [
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
    }


}