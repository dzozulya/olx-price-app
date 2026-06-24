<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Services\Dashboard\DashboardService;

class DashboardController extends Controller
{

    public function index(DashboardService $service)
    {
        $ads = $service->getAdvertisements();

        return view('dashboard.index', compact('ads'));
    }

    public function show(Advertisement $ad, DashboardService $service)
    {
        $history = $service->getPriceHistory($ad->last_currency,$ad->id);

        return view('dashboard.show', compact('ad', 'history'));
    }
}
