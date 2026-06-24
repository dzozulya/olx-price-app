<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'advertisement_id',
        'email',
        'verification_token',
        'verification_token_expires_at',
        'verified_at',
    ];
    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
    //
}
