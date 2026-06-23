<?php

namespace App\Jobs;

use App\Models\Advertisement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DispatchCheckPriceJob implements ShouldQueue
{
    use Queueable;
    public $timeout = 90;
    public $tries = 3;

    public function handle(): void
    {
        Advertisement::query()
            ->select('id')
            ->chunkById(100, function ($ads) {
                foreach ($ads as $ad) {
                    CheckPriceJob::dispatch($ad->id);
                }
            });
    }

    public function backoff(): array
    {
        return [10, 30, 60];
    }
}
