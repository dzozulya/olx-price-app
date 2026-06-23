<?php

namespace App\Jobs;

use App\Models\Advertisement;
use App\Models\PriceHistory;
use App\Services\Olx\OlxPriceFetcher;
use App\Events\PriceChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class CheckPriceJob implements ShouldQueue
{
    use Queueable;
    public $timeout = 90;
    public $tries = 3;



    public function __construct(
        public int $advertisementId
    ) {}

    public function handle(OlxPriceFetcher $fetcher): void
    {
        $lockKey = "olx:check:{$this->advertisementId}";

        // 🚨 Redis lock (анти-дубликаты)
        if (!Cache::add("olx:lock:{$this->advertisementId}", true, 300)) {
            return;
        }
        $ad = Advertisement::find($this->advertisementId);

        if (!$ad) {
            return;
        }

        $data = $fetcher->getPrice($ad->url);

        // сравнение через нормализованную цену
        if ($ad->last_price_uah === $data['price_uah']) {
            return;
        }


        PriceHistory::create([
            'advertisement_id' => $ad->id,
            'price' => $data['price'],
            'currency' => $data['currency'],
        ]);


        $ad->update([
            'last_price' => $data['price'],
            'last_currency' => $data['currency'],
            'last_price_uah' => $data['price_uah'],
            'last_checked_at' => now(),
        ]);


        PriceChanged::dispatch($ad->id, $data);


        self::dispatch($this->advertisementId)
            ->delay(now()->addMinutes(5));
    }
    public function backoff(): array
    {
        return [10, 30, 60];
    }
}
