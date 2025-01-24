<?php

namespace App\Console\Commands;

use App\Jobs\ProductsDownloadJob;
use Illuminate\Console\Command;


class DispatchProductsDownloadJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-products-download-job {limit? : The number of products to download from the source.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Products from shopify';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = (int) $this->argument('limit') ?: 50;

        ProductsDownloadJob::dispatch($limit);

        $this->info("ProductsDownloadJob dispatched with limit {$limit}");
        $this->info("The report will be saved in the 'reports' directory as 'products_report_<timestamp>.csv'.");

    }
}
