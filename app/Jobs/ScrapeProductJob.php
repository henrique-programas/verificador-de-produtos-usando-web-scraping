<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\PriceHistory;
use App\Services\ScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapeProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public Product $product) {}

    public function handle(ScraperService $scraper): void
    {
        $price = $scraper->fetchPrice($this->product->url, $this->product->css_selector);

        if (!$price) return;

        PriceHistory::create([
            'product_id' => $this->product->id,
            'price'      => $price,
            'scraped_at' => now(),
        ]);

        $this->product->update(['current_price' => $price]);
    }
}