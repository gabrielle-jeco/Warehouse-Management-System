<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Area;
use App\Models\Category;
use App\Models\Location;
use App\Models\Order;
use App\Models\TrafficCondition;
use App\Models\Vehicle;
use App\Models\WeatherCondition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DeliveryDataSeeder extends Seeder
{
    public function run()
    {
        $csv = File::get(storage_path('app/amazon_delivery.csv'));
        $rows = array_map('str_getcsv', explode("\n", $csv));
        $header = array_shift($rows);
        
        foreach ($rows as $row) {
            if (count($row) < 15) continue;
            
            // Create store location
            $storeLocation = Location::create([
                'latitude' => $row[3],
                'longitude' => $row[4],
                'area_id' => Area::where('name', trim($row[13]))->first()->id
            ]);

            // Create drop location
            $dropLocation = Location::create([
                'latitude' => $row[5],
                'longitude' => $row[6],
                'area_id' => Area::where('name', trim($row[13]))->first()->id
            ]);

            // Create agent
            $agent = Agent::create([
                'age' => $row[1],
                'rating' => $row[2],
                'vehicle_id' => Vehicle::where('type', trim($row[12]))->first()->id
            ]);

            // Create order
            Order::create([
                'order_id' => $row[0],
                'agent_id' => $agent->id,
                'store_location_id' => $storeLocation->id,
                'drop_location_id' => $dropLocation->id,
                'order_date' => $row[7],
                'order_time' => $row[8],
                'pickup_time' => $row[9],
                'weather_id' => WeatherCondition::where('name', trim($row[10]))->first()->id,
                'traffic_id' => TrafficCondition::where('level', trim($row[11]))->first()->id,
                'delivery_time' => $row[14],
                'category_id' => Category::where('name', trim($row[15]))->first()->id
            ]);
        }
    }
} 