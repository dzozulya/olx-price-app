<?php

namespace App\Services\Olx;

class OlxPriceFetcher
{
    public function __construct(
        private OlxClient $client,
        private OlxPriceParcer $parser
    ) {}

    public function fetch(string $url): ?array
    {
        $html = $this->client->getHtml($url);

        if (!$html) {
            return null;
        }

        return $this->parser->parse($html, $url);
    }
}
