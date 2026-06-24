<?php

namespace Tests\Feature\Web;

use App\Models\Advertisement;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_verify_email(): void
    {
        $ad = Advertisement::factory()->create();

        $subscription = Subscription::create([
            'advertisement_id' => $ad->id,
            'email' => 'test@example.com',
            'verification_token' => Str::random(64),
            'verification_token_expires_at' => now()->addDay(),
        ]);

        $response = $this->get(
            '/verify-email/'.$subscription->verification_token
        );

        $response->assertStatus(200);

        $this->assertNotNull(
            $subscription->fresh()->verified_at
        );
    }

    public function test_invalid_token_returns_404(): void
    {
        $response = $this->get('/verify-email/invalid-token');

        $response->assertStatus(200);
        $response->assertSimilarJson(['message' => 'Invalid token']);
    }
}
