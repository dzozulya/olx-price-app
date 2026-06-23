<?php

namespace App\Services;

use App\Models\Advertisement;
use http\Exception\InvalidArgumentException;

class AdvertismentService
{
    public function findOrCreate(string $url): Advertisement
    {
        $olxId = $this->extractOlxId($url);

        return Advertisement::firstOrCreate(
            ['olx_id' => $olxId],
            [
                'url' => $url,
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

    private function extractOlxId(string $url): string
    {
        preg_match('/ID(\d+)/', $url, $matches);
        if (!isset($matches[1])) {
            throw new InvalidArgumentException('Invalid OLX URL');
        }

        return $matches[1];
    }

}
