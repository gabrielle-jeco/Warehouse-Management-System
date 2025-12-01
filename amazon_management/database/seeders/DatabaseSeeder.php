<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            WeatherConditionSeeder::class,
            TrafficConditionSeeder::class,
            VehicleSeeder::class,
            AreaSeeder::class,
            CategorySeeder::class,
            DeliveryDataSeeder::class,
        ]);
    }
}
