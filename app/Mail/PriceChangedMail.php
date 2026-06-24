<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class PriceChangedMail extends Mailable
{
    public function __construct(
        public string $url,
        public int $price,
        public string $currency
    ) {}

    public function build()
    {
        return $this->subject('OLX price changed')
            ->view('emails.price-changed');
    }
}
