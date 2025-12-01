<?php

namespace Database\Seeders;

use App\Models\WeatherCondition;
use Illuminate\Database\Seeder;

class WeatherConditionSeeder extends Seeder
{
    public function run()
    {
        $conditions = ['Sunny', 'Windy', 'Sandstorms', 'Stormy', 'Cloudy'];
        
        foreach ($conditions as $condition) {
            WeatherCondition::create(['name' => $condition]);
        }
    }
} 