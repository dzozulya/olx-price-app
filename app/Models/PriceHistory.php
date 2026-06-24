<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    protected $fillable = [
        'advertisement_id',
        'price_value',
        'currency',
        'price_value',
        'currency'
    ];
    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
