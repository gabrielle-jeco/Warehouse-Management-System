<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'agent_id',
        'store_location_id',
        'drop_location_id',
        'order_date',
        'order_time',
        'pickup_time',
        'weather_id',
        'traffic_id',
        'delivery_time',
        'category_id'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function storeLocation()
    {
        return $this->belongsTo(Location::class, 'store_location_id');
    }

    public function dropLocation()
    {
        return $this->belongsTo(Location::class, 'drop_location_id');
    }

    public function weather()
    {
        return $this->belongsTo(WeatherCondition::class, 'weather_id');
    }

    public function traffic()
    {
        return $this->belongsTo(TrafficCondition::class, 'traffic_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
} 