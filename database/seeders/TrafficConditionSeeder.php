<?php

namespace Database\Seeders;

use App\Models\TrafficCondition;
use Illuminate\Database\Seeder;

class TrafficConditionSeeder extends Seeder
{
    public function run()
    {
        $conditions = ['Low', 'Medium', 'High', 'Jam'];
        
        foreach ($conditions as $condition) {
            TrafficCondition::create(['level' => $condition]);
        }
    }
} 