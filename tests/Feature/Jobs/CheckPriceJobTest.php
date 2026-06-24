<?php

namespace Tests\Unit\Jobs;

use App\Jobs\CheckPriceJob;
use App\Models\Advertisement;
use App\Models\PriceHistory;
use App\Services\Olx\OlxPriceFetcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckPriceJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_price_history_is_created(): void
    {
        $ad = Advertisement::factory()->create([
            'olx_id' => 'ID123',
            'url' => 'https://olx.ua/test',
        ]);

        $fetcher = $this->createMock(
            OlxPriceFetcher::class
        );

        $fetcher->method('fetch')
            ->willReturn([
                'title' => 'Test Ad',
                'price_value' => 6500,
                'currency' => 'USD',
                'price_uah' => 270000,
            ]);

        $job = new CheckPriceJob($ad->id);

        $job->handle($fetcher);

        $this->assertDatabaseHas('price_histories', [
            'advertisement_id' => $ad->id,
            'price_value' => 6500,
        ]);
    }
}
