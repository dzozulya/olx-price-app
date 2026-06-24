<?php

namespace App\Services;

use App\Jobs\CheckPriceJob;
use App\Mail\VerifyEmailMail;
use App\Models\Advertisement;
use App\Models\Subscription;
use App\Services\Olx\OlxPriceFetcher;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * @property $advertisementService
 * #
 */
class SubscriptionService
{

    public function __construct(
        private AdvertisementService $advertisementService

    ) {}

    public function subscribe(string $url,string $email): Subscription
    {
        $ad=$this->advertisementService->findOrCreate($url);

        $subscription = Subscription::create([
            'advertisement_id' => $ad->id,
            'email' => $email,
            'verification_token' => Str::random(64),
            'verification_token_expires_at' => now()->addHours(24),
        ]);
        Mail::to($subscription->email)->send(new VerifyEmailMail($subscription));

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
    public function verifyEmail(string $token)
    {
        $sub = Subscription::where('verification_token', $token)
            ->first();

        if (!$sub) {
            return['message' => 'Invalid token'];
        }

        if ($sub->verified_at) {
            return['message' => 'Already verified'];
        }

        if (
            !$sub->verification_token_expires_at ||
            now()->greaterThan($sub->verification_token_expires_at)
        ) {
            return ['message' => 'Token expired'];
        }

        $sub->update([
            'verified_at' => now(),
            'verification_token' => null,
            'verification_token_expires_at' => null,
        ]);

        return ['status' => 'verified'];
    }


}
