<?php

namespace Tests\Unit;

use App\Services\Olx\OlxPriceParcer;
use Tests\TestCase;

class OlxPriceParserTest extends TestCase
{
    /** @test */
    public function it_parses_valid_json_ld_price()
    {
        $html = '
            <script type="application/ld+json">
            {
                "offers": {
                    "price": "1500",
                    "priceCurrency": "USD"
                }
            }
            </script>
        ';

        $parser = new OlxPriceParcer();

        $result = $parser->parse($html, 'https://olx.ua/item/ID123');

        $this->assertEquals([
            'olx_id' => '123',
            'title' => 'Unknown OLX Ad',
            'price_value' => 1500,
            'currency' => 'USD',
        ], $result);
    }

    /** @test */
    public function it_returns_null_when_id_missing()
    {
        $parser = new OlxPriceParcer();

        $result = $parser->parse('<html></html>', 'https://olx.ua/item/');

        $this->assertNull($result);
    }
}
