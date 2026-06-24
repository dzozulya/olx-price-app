<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Services\Olx\OlxPriceFetcher;
use http\Exception\InvalidArgumentException;

class AdvertisementService
{

    public function __construct(
        private  OlxPriceFetcher $fetcher
    )
    {
    }

    public function findOrCreate(string $url): Advertisement
    {
        $olxId = $this->extractOlxId($url);
        $data = $this->fetcher->fetch($url);




        return Advertisement::firstOrCreate(
            ['olx_id' => $olxId],
            [
                'url' => $url,
                'title'=>$data['title']
            ]
        );

    }

    public function updatePrice(Advertisement $ad, int $price) : void
    {
        $ad->update([
            'last_price' => $price,
            'last_checked_at' => now(),
        ]);

    }

    function extractOlxId(string $url): ?string
    {
        if (preg_match('/-ID([a-zA-Z0-9]+)\.html/', $url, $m)) {
            return $m[1];
        }

        return null;
    }

}
