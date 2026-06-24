<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;
    protected $fillable = [
        'olx_id',
        'url',
        'title',
        'last_price_value',
        'last_currency',
        'last_checked_at',
        'last_notified_at',
    ];
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class);
    }
}


