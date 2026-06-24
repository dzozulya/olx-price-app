<?php

namespace App\Mail;

use App\Models\Subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription
    ) {}

    public function build()
    {
        return $this->subject('Подтвердите подписку на изменение цены')
            ->view('emails.verify-email')
            ->with([
                'email' => $this->subscription->email,
                'url' =>URL::to('/verify-email/' . $this->subscription->verification_token),
                'expiresAt' => $this->subscription->verification_token_expires_at,
            ]);
    }
}
