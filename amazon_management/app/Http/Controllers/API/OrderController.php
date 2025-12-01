<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Agent;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with([
            'agent.vehicle', 
            'category', 
            'storeLocation.area',
            'dropLocation.area',
            'traffic',
            'weather'
        ]);
        
        // Global search
        if ($request->filled('global_search')) {
            $searchTerm = $request->global_search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('order_id', 'like', "%{$searchTerm}%")
                  ->orWhereHas('category', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('agent', function($q) use ($searchTerm) {
                      $q->where('age', 'like', "%{$searchTerm}%")
                        ->orWhere('rating', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('weather', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('agent.vehicle', function($q) use ($searchTerm) {
                      $q->where('type', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('storeLocation.area', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('dropLocation.area', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('traffic', function($q) use ($searchTerm) {
                      $q->where('level', 'like', "%{$searchTerm}%");
                  })
                  ->orWhere('order_date', 'like', "%{$searchTerm}%")
                  ->orWhere('order_time', 'like', "%{$searchTerm}%")
                  ->orWhere('pickup_time', 'like', "%{$searchTerm}%")
                  ->orWhere('delivery_time', 'like', "%{$searchTerm}%");
            });
        }

        // Custom Filters
        if ($request->filled('order_id')) {
            $query->where('order_id', 'like', '%' . $request->order_id . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        // Add new filters
        if ($request->filled('vehicle_type')) {
            $query->whereHas('agent', function($q) use ($request) {
                $q->where('vehicle_id', $request->vehicle_type);
            });
        }

        if ($request->filled('traffic_id')) {
            $query->where('traffic_id', $request->traffic_id);
        }

        if ($request->filled('rating')) {
            $query->whereHas('agent', function($q) use ($request) {
                $q->where('rating', '>=', $request->rating);
            });
        }

        // Add area filter
        if ($request->filled('area_id')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('storeLocation', function($sq) use ($request) {
                    $sq->where('area_id', $request->area_id);
                })->orWhereHas('dropLocation', function($sq) use ($request) {
                    $sq->where('area_id', $request->area_id);
                });
            });
        }

        // Time range filters
        if ($request->filled('order_time_from')) {
            $query->where('order_time', '>=', $request->order_time_from);
        }
        if ($request->filled('order_time_to')) {
            $query->where('order_time', '<=', $request->order_time_to);
        }
        if ($request->filled('pickup_time_from')) {
            $query->where('pickup_time', '>=', $request->pickup_time_from);
        }
        if ($request->filled('pickup_time_to')) {
            $query->where('pickup_time', '<=', $request->pickup_time_to);
        }

        // Store location range filters
        if ($request->filled(['store_lat_from', 'store_lat_to', 'store_long_from', 'store_long_to'])) {
            $query->whereHas('storeLocation', function($q) use ($request) {
                $q->whereBetween('latitude', [$request->store_lat_from, $request->store_lat_to])
                  ->whereBetween('longitude', [$request->store_long_from, $request->store_long_to]);
            });
        }

        // Drop location range filters
        if ($request->filled(['drop_lat_from', 'drop_lat_to', 'drop_long_from', 'drop_long_to'])) {
            $query->whereHas('dropLocation', function($q) use ($request) {
                $q->whereBetween('latitude', [$request->drop_lat_from, $request->drop_lat_to])
                  ->whereBetween('longitude', [$request->drop_long_from, $request->drop_long_to]);
            });
        }

        // Weather condition filter
        if ($request->filled('weather_id')) {
            $query->where('weather_id', $request->weather_id);
        }

        // Agent age range filter
        if ($request->filled('agent_age_from') || $request->filled('agent_age_to')) {
            $query->whereHas('agent', function($q) use ($request) {
                if ($request->filled('agent_age_from')) {
                    $q->where('age', '>=', $request->agent_age_from);
                }
                if ($request->filled('agent_age_to')) {
                    $q->where('age', '<=', $request->agent_age_to);
                }
            });
        }

        // Get total and filtered record counts BEFORE applying pagination
        $totalRecords = Order::count();
        $filteredRecords = clone $query;
        $filteredCount = $filteredRecords->count();
        
        // Sorting
        if ($request->order && isset($request->order[0])) {
            $columnIndex = $request->order[0]['column'];
            $columnName = $request->columns[$columnIndex]['data'];
            $columnDirection = $request->order[0]['dir'];
            
            switch($columnName) {
                case 'agent':
                    $query->join('agents', 'orders.agent_id', '=', 'agents.id')
                          ->orderBy('agents.rating', $columnDirection)
                          ->select('orders.*');
                    break;
                case 'category':
                    $query->join('categories', 'orders.category_id', '=', 'categories.id')
                          ->orderBy('categories.name', $columnDirection)
                          ->select('orders.*');
                    break;
                default:
                    if (in_array($columnName, ['order_id', 'order_date', 'order_time', 'pickup_time', 'delivery_time'])) {
                        $query->orderBy($columnName, $columnDirection);
                    }
            }
        }
        
        // Pagination
        $orders = $query->skip($request->start ?? 0)
                        ->take($request->length ?? 10)
                        ->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredCount,
            'data' => $orders
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|unique:orders',
            'agent_age' => 'required|numeric',
            'agent_rating' => 'required|numeric',
            'vehicle_type' => 'required|exists:vehicles,id',
            'store_latitude' => 'required|numeric',
            'store_longitude' => 'required|numeric',
            'drop_latitude' => 'required|numeric',
            'drop_longitude' => 'required|numeric',
            'area_id' => 'required|exists:areas,id',
            'order_date' => 'required|date',
            'order_time' => 'required',
            'pickup_time' => 'required',
            'weather_id' => 'required|exists:weather_conditions,id',
            'traffic_id' => 'required|exists:traffic_conditions,id',
            'delivery_time' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Create store location
        $storeLocation = Location::create([
            'latitude' => $validated['store_latitude'],
            'longitude' => $validated['store_longitude'],
            'area_id' => $validated['area_id']
        ]);

        // Create drop location
        $dropLocation = Location::create([
            'latitude' => $validated['drop_latitude'],
            'longitude' => $validated['drop_longitude'],
            'area_id' => $validated['area_id']
        ]);

        // Create agent
        $agent = Agent::create([
            'age' => $validated['agent_age'],
            'rating' => $validated['agent_rating'],
            'vehicle_id' => $validated['vehicle_type']
        ]);

        // Create order
        $order = Order::create([
            'order_id' => $validated['order_id'],
            'agent_id' => $agent->id,
            'store_location_id' => $storeLocation->id,
            'drop_location_id' => $dropLocation->id,
            'order_date' => $validated['order_date'],
            'order_time' => $validated['order_time'],
            'pickup_time' => $validated['pickup_time'],
            'weather_id' => $validated['weather_id'],
            'traffic_id' => $validated['traffic_id'],
            'delivery_time' => $validated['delivery_time'],
            'category_id' => $validated['category_id']
        ]);

        return response()->json($order->load(['agent', 'storeLocation', 'dropLocation', 'weather', 'traffic', 'category']), 201);
    }

    public function show(Order $order)
    {
        return $order->load([
            'agent.vehicle',
            'category',
            'storeLocation.area',
            'dropLocation.area',
            'weather',
            'traffic'
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_id' => 'required|unique:orders,order_id,' . $order->id,
            'agent_age' => 'required|numeric',
            'agent_rating' => 'required|numeric',
            'vehicle_type' => 'required|exists:vehicles,id',
            'store_latitude' => 'required|numeric',
            'store_longitude' => 'required|numeric',
            'drop_latitude' => 'required|numeric',
            'drop_longitude' => 'required|numeric',
            'area_id' => 'required|exists:areas,id',
            'order_date' => 'required|date',
            'order_time' => 'required',
            'pickup_time' => 'required',
            'weather_id' => 'required|exists:weather_conditions,id',
            'traffic_id' => 'required|exists:traffic_conditions,id',
            'delivery_time' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Update store location
        $order->storeLocation->update([
            'latitude' => $validated['store_latitude'],
            'longitude' => $validated['store_longitude'],
            'area_id' => $validated['area_id']
        ]);

        // Update drop location
        $order->dropLocation->update([
            'latitude' => $validated['drop_latitude'],
            'longitude' => $validated['drop_longitude'],
            'area_id' => $validated['area_id']
        ]);

        // Update agent
        $order->agent->update([
            'age' => $validated['agent_age'],
            'rating' => $validated['agent_rating'],
            'vehicle_id' => $validated['vehicle_type']
        ]);

        // Update order
        $order->update([
            'order_id' => $validated['order_id'],
            'order_date' => $validated['order_date'],
            'order_time' => $validated['order_time'],
            'pickup_time' => $validated['pickup_time'],
            'weather_id' => $validated['weather_id'],
            'traffic_id' => $validated['traffic_id'],
            'delivery_time' => $validated['delivery_time'],
            'category_id' => $validated['category_id']
        ]);

        return response()->json($order->load(['agent', 'storeLocation', 'dropLocation', 'weather', 'traffic', 'category']));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $query = Order::with(['agent', 'category']);

        if ($request->has('order_id')) {
            $query->where('order_id', 'like', '%' . $request->order_id . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('date_from')) {
            $query->where('order_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('order_date', '<=', $request->date_to);
        }

        // Total records
        $totalRecords = $query->count();
        
        // Pagination
        $orders = $query->skip($request->start ?? 0)
                        ->take($request->length ?? 10)
                        ->get();

        return response()->json([
            'draw' => $request->draw ?? 1,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $orders
        ]);
    }
} 