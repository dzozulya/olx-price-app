<?php

namespace App\Listeners;

use App\Events\PriceChanged;
use App\Jobs\NotifySubscribersJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPriceChangedNotification
{
    public function handle(PriceChanged $event): void
    {
        NotifySubscribersJob::dispatch(
            $event->advertisementId,
            $event->data
        );
    }
}
