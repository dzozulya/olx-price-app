<?php

namespace Tests\Unit\Jobs;

use App\Jobs\NotifySubscribersJob;
use App\Mail\PriceChangedMail;
use App\Models\Advertisement;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotifySubscribersJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_is_sent_to_verified_subscriber(): void
    {
        Mail::fake();

        $ad = Advertisement::factory()->create();
        logger()->info(json_encode($ad));

        Subscription::create([
            'advertisement_id' => $ad->id,
            'email' => 'verified@test.com',
            'verified_at' => now(),
        ]);

        $job = new NotifySubscribersJob(
            $ad->id,
            [
                'price' => 6500,
                'currency' => 'USD',
            ]
        );

        $job->handle();

        Mail::assertSent(PriceChangedMail::class);
    }

    public function test_notification_is_not_sent_to_unverified_subscriber(): void
    {
        Mail::fake();

        $ad = Advertisement::factory()->create();

        Subscription::create([
            'advertisement_id' => $ad->id,
            'email' => 'pending@test.com',
        ]);

        $job = new NotifySubscribersJob(
            $ad->id,
            [
                'price' => 6500,
                'currency' => 'USD',
            ]
        );

        $job->handle();

        Mail::assertNothingSent();
    }
}
