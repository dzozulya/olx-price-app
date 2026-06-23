<?php

namespace App\Services;

use App\Jobs\CheckPriceJob;
use App\Models\Advertisement;
use App\Models\Subscription;

class SubscriptionService
{

    public function __construct(
        private AdvertisementService $advertisementService
    ) {}

    public function subscribe(string $url,string $email): Subscription
    {
        $ad=$this->advertisementService->findOrCreate($url);

        $subscription = Subscription::firstOrCreate([
            'advertisement_id' => $ad->id,
            'email' => $email,
        ]);

        $this->dispatchTrackingJobOnce($ad);

        return $subscription;



    }
    private function dispatchTrackingJobOnce(Advertisement $ad): void
    {
        $key = "olx:job:{$ad->id}";

        if (cache()->has($key)) {
            return;
        }
        cache()->put($key, true, now()->addMinutes(10));

        CheckPriceJob::dispatch($ad->id);

    }


}
