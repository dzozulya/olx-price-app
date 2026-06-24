<?php

namespace App\Services\Olx;

class OlxPriceParcer
{
    public function parse(string $html, string $url): ?array
    {
        $olxId = $this->extractId($url);
        $title = $this->extractTitle($html);
        $price = $this->extractPrice($html);

        if (!$olxId || !$price || !$price['price_value']) {
            return null;
        }

        return [
            'olx_id' => $olxId,
            'title' => $title ?: 'Unknown OLX Ad',
            'price_value' => $price['price_value'],
            'currency' => $price['currency'],
        ];
    }

    private function extractId(string $url): ?string
    {
        if (preg_match('/ID([a-zA-Z0-9]+)/', $url, $m)) {
            return $m[1];
        }
        return null;
    }

    private function extractTitle(string $html): ?string
    {
        if (preg_match('/data-testid="offer_title".*?<h4[^>]*>(.*?)<\/h4>/si', $html, $m)) {
            return trim(strip_tags($m[1]));
        }

        if (preg_match('/property="og:title"\s+content="([^"]+)"/i', $html, $m)) {
            return trim($m[1]);
        }

        return null;
    }

    private function extractPrice(string $html): ?array
    {
        if (preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $m)) {

            $json = json_decode($m[1], true);

            $price = $json['offers']['price'] ?? null;
            $currency = $json['offers']['priceCurrency'] ?? 'UAH';

            if ($price !== null && $price !== '') {
                return [
                    'price_value' => $this->toInt($price),
                    'currency' => $this->mapCurrency($currency),
                ];
            }
        }

        if (preg_match('/"price"\s*:\s*"?([\d\s]+)/', $html, $m)) {
            return [
                'price_value' => $this->toInt($m[1]),
                'currency' => $this->detectCurrency($html) ?: 'UAH',
            ];
        }

        if (preg_match('/(\$|€|грн|UAH|USD|EUR)?\s*([\d\s]{2,})\s*(\$|€|грн|UAH|USD|EUR)?/ui', $html, $m)) {
            $currency = $m[1] ?: $m[3] ?: 'UAH';

            return [
                'price_value' => $this->toInt($m[2]),
                'currency' => $this->mapCurrency($currency),
            ];
        }

        return null;
    }
    private function mapCurrency(string $currency): string
    {
        return match (strtoupper(trim($currency))) {
            'UAH', 'ГРН', '₴' => 'UAH',
            '$', 'USD' => 'USD',
            '€', 'EUR' => 'EUR',
            default => 'UAH',
        };
    }

    private function toInt(string $value): int
    {
        return (int) preg_replace('/[^\d]/', '', $value);
    }

    private function detectCurrency(string $html): string
    {
        if (preg_match('/"priceCurrency"\s*:\s*"([A-Z]{3})"/i', $html, $m)) {
            return $m[1];
        }
         return 'UAH';



    }
}
