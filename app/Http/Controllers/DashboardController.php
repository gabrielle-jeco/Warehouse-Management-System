<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\WeatherCondition;
use App\Models\TrafficCondition;
use App\Models\Vehicle;
use App\Models\Area;

class DashboardController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $weatherConditions = WeatherCondition::all();
        $trafficConditions = TrafficCondition::all();
        $vehicles = Vehicle::all();
        $areas = Area::all();
        
        return view('dashboard', compact(
            'categories',
            'weatherConditions',
            'trafficConditions',
            'vehicles',
            'areas'
        ));
    }
} 