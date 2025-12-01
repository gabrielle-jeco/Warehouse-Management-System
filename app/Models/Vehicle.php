<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ['type'];

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }
} 