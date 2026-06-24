<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use App\Services\Dashboard\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class DashboardPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dashboard_index_page_returns_200_and_view()
    {
        $mock = Mockery::mock(DashboardService::class);
        $mock->shouldReceive('getAdvertisements')
            ->once()
            ->andReturn(collect());

        $this->app->instance(DashboardService::class, $mock);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
        $response->assertViewHas('ads');
    }

    /** @test */
    public function dashboard_show_page_returns_ad_and_history()
    {
        $ad = Advertisement::factory()->create();

        $mock = Mockery::mock(DashboardService::class);

        $mock->shouldReceive('getPriceHistory')
            ->once()
            ->with($ad->last_currency, $ad->id)
            ->andReturn(collect());

        $this->app->instance(DashboardService::class, $mock);

        $response = $this->get("/dashboard/{$ad->id}");

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.show');
        $response->assertViewHas('ad');
        $response->assertViewHas('history');
    }
}
