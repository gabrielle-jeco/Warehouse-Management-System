<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run()
    {
        $types = ['motorcycle', 'scooter'];
        
        foreach ($types as $type) {
            Vehicle::create(['type' => $type]);
        }
    }
} 