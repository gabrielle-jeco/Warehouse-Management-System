<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['latitude', 'longitude', 'area_id'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function storeOrders()
    {
        return $this->hasMany(Order::class, 'store_location_id');
    }

    public function dropOrders()
    {
        return $this->hasMany(Order::class, 'drop_location_id');
    }
} 