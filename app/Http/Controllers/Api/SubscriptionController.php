<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function store(
        StoreSubscriptionRequest $request,
        SubscriptionService $subscriptionService
    ): JsonResponse {
        $subscription = $subscriptionService->subscribe(
            $request->validated('url'),
            $request->validated('email')
        );

        return response()->json([
            'success' => true,
            'message' => 'Subscription created',
            'data' => [
                'id' => $subscription->id,
                'email' => $subscription->email,
            ]
        ], 201);
    }
}
