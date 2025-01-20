<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use PHPShopify\ShopifySDK;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportGenerated;
use App\Models\User;



class GenerateProductsReportJob implements ShouldQueue
{
    use Queueable;

    protected $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $shopify = app(ShopifySDK::class);
        try {
            $products = $shopify->Product->get(['limit' => 5]);
        } catch (\Exception $e) {
            Log::error('Error fetching products from Shopify: ' . $e->getMessage());
            return;
        }
    

        $directory = dirname($this->filePath);

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $handle = fopen($this->filePath, 'w');
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

        $users = User::where('daily_report', true)->get();

        if ($users->isEmpty()) {
            Log::info('No users with daily_report enabled. Emails will not be sent.');
            return;
        }
        
        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new ReportGenerated($this->filePath));
                Log::info('Email sent to: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Error sending email to ' . $user->email . ': ' . $e->getMessage());
            }
        }
    }
}
