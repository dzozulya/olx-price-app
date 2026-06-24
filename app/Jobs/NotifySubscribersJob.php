<?php

namespace App\Jobs;

use App\Models\Advertisement;
use App\Mail\PriceChangedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class NotifySubscribersJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 90;
    public $tries = 3;

    public function __construct(
        public string $advertisementId,
        public array $data
    ) {}

    public function handle(): void
    {
        $ad = Advertisement::with('subscriptions')
            ->find($this->advertisementId);

        if (!$ad) {
            return;
        }

        $price = $this->data['price'] ?? null;
        $currency = $this->data['currency'] ?? null;

        if ($price === null || $currency === null) {
            return;
        }

        foreach ($ad->subscriptions ?? [] as $sub) {

            if (empty($sub->verified_at)) {
                continue;
            }

            Mail::to($sub->email)->send(
                new PriceChangedMail(
                    $ad->olx_id,
                    $price,
                    $currency
                )
            );
        }
    }
    public function backoff(): array
    {
        return [10, 30, 60];
    }
}
