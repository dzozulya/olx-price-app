<?php

namespace App\Jobs;

use App\Models\Advertisement;
use App\Models\PriceHistory;
use App\Services\Olx\OlxPriceFetcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckPriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $advertisementId
    ) {}

    public function handle(OlxPriceFetcher $fetcher): void
    {
        $ad = Advertisement::find($this->advertisementId);

        if (!$ad) {
            return;
        }

        $data = $fetcher->fetch($ad->url);

        if (!$data || !isset($data['price_value'])) {
            return;
        }
        logger()->info(json_encode($data));
        $priceValue = (int) $data['price_value'];
        $currency   = strtoupper($data['currency'] ?? 'UAH');

        $ad->update([
            'last_price_value' => $priceValue,
            'last_currency'    => $currency,
            'last_checked_at'  => now(),
            'title'            => $data['title'] ?? $ad->title,
        ]);

        $last = PriceHistory::where('advertisement_id', $ad->id)
            ->latest()
            ->first();

        $lastPrice = $last?->price_value;

        if ($lastPrice === null || (int)$lastPrice !== (int)$priceValue) {

            NotifySubscribersJob::dispatch(
                $ad->title,
                [
                    'price' => $priceValue,
                    'currency' => $currency,
                ]
            );

            PriceHistory::create([
                'advertisement_id' => $ad->id,
                'price_value'      => $priceValue,
                'currency'         => $currency,
            ]);
        }

    }

    public function failed(\Throwable $e): void
    {
        logger()->error('CheckPriceJob FAILED', [
            'advertisement_id' => $this->advertisementId,
            'error' => $e->getMessage(),
        ]);
    }
}
