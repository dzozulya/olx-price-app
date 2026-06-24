<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Subscription;
use App\Services\Olx\OlxPriceFetcher;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    public function index()
    {
        return view('subscriptions.index');
    }

    public function store(StoreSubscriptionRequest $request, SubscriptionService $subscriptionService,OlxPriceFetcher $fetcher)
    {
        $subscriptionService->subscribe(
            $request->validated('url'),
            $request->validated('email')
        );
        return redirect()
            ->back()
            ->with('success', 'Підписка успішно створена.');

    }
    public function verifyEmail(string $token, SubscriptionService $subscriptionService)
    {

        return response()->json($subscriptionService->verifyEmail($token));
    }
}
