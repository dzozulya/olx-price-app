<?php

namespace App\Services\Dashboard;

use App\Models\Advertisement;
use App\Models\PriceHistory;

class DashboardService
{
    public function getAdvertisements()
    {
        return Advertisement::latest()->get();
    }

    public function getPriceHistory(string$toCurrency, int $adId)
    {
        return PriceHistory::where('advertisement_id', $adId)
            ->orderBy('created_at')
            ->get()
            ->map(function ($item) use ($toCurrency,) {

                $value = $item->price_value;


                return [
                    'price' => round($value),
                    'currency' => $toCurrency,
                    'date' => $item->created_at->format('Y-m-d H:i'),
                ];
            });
    }
}
