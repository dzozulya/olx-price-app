<?php

namespace Tests\Unit;

use App\Models\Advertisement;
use App\Models\PriceHistory;
use App\Services\Dashboard\DashboardService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_returns_latest_advertisements()
    {
        $ads = new Collection([
            new Advertisement(),
            new Advertisement(),
        ]);

        $mock = Mockery::mock('alias:' . Advertisement::class);

        $mock->shouldReceive('latest')
            ->once()
            ->andReturnSelf();

        $mock->shouldReceive('get')
            ->once()
            ->andReturn($ads);

        $service = new DashboardService();

        $result = $service->getAdvertisements();

        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_returns_formatted_price_history()
    {
        $adId = 1;
        $currency = 'USD';

        $historyItem = new PriceHistory();
        $historyItem->price_value = 123.45;
        $historyItem->created_at = Carbon::parse('2026-01-01 10:00:00');

        $mock = Mockery::mock('alias:' . PriceHistory::class);

        $mock->shouldReceive('where')
            ->once()
            ->with('advertisement_id', $adId)
            ->andReturnSelf();

        $mock->shouldReceive('orderBy')
            ->once()
            ->with('created_at')
            ->andReturnSelf();

        $mock->shouldReceive('get')
            ->once()
            ->andReturn(collect([$historyItem]));

        $service = new DashboardService();

        $result = $service->getPriceHistory($currency, $adId);

        $this->assertCount(1, $result);

        $this->assertEquals([
            'price' => 123,
            'currency' => 'USD',
            'date' => '2026-01-01 10:00',
        ], $result->first());
    }
}
