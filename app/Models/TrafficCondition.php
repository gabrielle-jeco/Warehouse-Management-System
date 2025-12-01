<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrafficCondition extends Model
{
    protected $fillable = ['level'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'traffic_id');
    }
} 