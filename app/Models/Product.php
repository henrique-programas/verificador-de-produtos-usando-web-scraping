<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'url', 'css_selector',
        'current_price', 'alert_below', 'email_alert',
    ];

    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class);
    }
}