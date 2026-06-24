<?php

namespace Tests\Unit;

use App\Services\Olx\OlxClient;
use App\Services\Olx\OlxPriceFetcher;
use App\Services\Olx\OlxPriceParcer;
use Tests\TestCase;

class OlxPriceFetcherTest extends TestCase
{
    /** @test */
    public function it_returns_null_when_html_not_loaded()
    {
        $client = $this->mock(OlxClient::class);
        $client->shouldReceive('getHtml')->andReturn(null);

        $fetcher = new OlxPriceFetcher($client, new OlxPriceParcer());

        $this->assertNull($fetcher->fetch('https://olx.ua/item/ID123'));
    }

    /** @test */
    public function it_returns_parsed_result()
    {
        $html = '
            <script type="application/ld+json">
            {
                "offers": {
                    "price": "2000",
                    "priceCurrency": "USD"
                }
            }
            </script>
        ';

        $client = $this->mock(OlxClient::class);
        $client->shouldReceive('getHtml')->andReturn($html);

        $fetcher = new OlxPriceFetcher($client, new OlxPriceParcer());

        $result = $fetcher->fetch('https://olx.ua/item/ID999');

        $this->assertEquals(2000, $result['price_value']);
        $this->assertEquals('USD', $result['currency']);
    }
}
