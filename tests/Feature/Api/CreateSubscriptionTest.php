<?php

namespace Tests\Feature\Api;

use App\Mail\VerifyEmailMail;
use App\Models\Advertisement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CreateSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_subscription(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/subscriptions', [
            'url' => 'https://www.olx.ua/d/uk/obyavlenie/prodam-pkap-mitsubishi-l200-ID10IS6f.html',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('subscriptions', [
            'email' => 'test@example.com',
        ]);

        Mail::assertSent(VerifyEmailMail::class);
    }

    public function test_email_is_required(): void
    {
        $response = $this->postJson('/api/subscriptions', [
            'url' => 'https://olx.ua/test',
        ]);

        $response->assertStatus(422);
    }

    public function test_url_is_required(): void
    {
        $response = $this->postJson('/api/subscriptions', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(422);
    }
}
