<?php

namespace App\Services\Olx;

use App\Services\Finance\NbuExchangeService;

class OlxPriceFetcher
{
    public function __construct(
        private NbuExchangeService $nbu
    ) {}

    public function getPrice(string $url): array
    {
        $html = $this->fetchHtml($url);

        [$price, $currency] = $this->extract($html);

        return [
            'price' => $price,
            'currency' => $currency,
            'price_uah' => $this->nbu->toUah($price, $currency),
        ];
    }

    private function fetchHtml(string $url): string
    {
        return file_get_contents($url);
    }

    private function extract(string $html): array
    {
        preg_match(
            '/([0-9\s]+)\s*(грн|\$|usd|eur|€)?/iu',
            $html,
            $m
        );

        $price = (int) str_replace(' ', '', $m[1] ?? 0);

        $currency = strtolower($m[2] ?? 'uah');

        return [$price, $this->normalizeCurrency($currency)];
    }

    private function normalizeCurrency(string $currency): string
    {
        return match ($currency) {
            '$', 'usd' => 'USD',
            '€', 'eur' => 'EUR',
            'грн', 'uah', '' => 'UAH',
            default => 'UAH',
        };
    }
}
