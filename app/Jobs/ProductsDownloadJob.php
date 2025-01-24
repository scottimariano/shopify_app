<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use PHPShopify\ShopifySDK;

class ProductsDownloadJob implements ShouldQueue
{
    use Queueable;

    public int $limit;

    /**
     * Create a new job instance.
     */
    public function __construct(int $limit = null)
    {
        $this->limit = $limit ?: 50;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $shopify = app(ShopifySDK::class);
            $products = $shopify->Product->get(['limit' => $this->limit]);
        } catch (\Exception $e) {
            Log::error('Error fetching products from Shopify: ' . $e->getMessage());
            return;
        }

        $dateTime = date('Y-m-d_H:i:s');
        $fileName = "reports/products_report_{$dateTime}.csv";

        $directory = dirname($fileName);

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $handle = fopen($fileName, 'w');
        fputcsv($handle, ['ID', 'Title', 'Description', 'Price']);

        foreach ($products as $product) {
            fputcsv($handle, [
                $product['id'],
                $product['title'],
                strip_tags($product['body_html']),
                $product['variants'][0]['price'] ?? 'N/A',
            ]);
        }

        fclose($handle);
    }
}
