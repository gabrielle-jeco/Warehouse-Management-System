<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Amazon Delivery Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
    <style>
        .modal-lg {
            max-width: min(900px, 95vw);
        }
        
        .form-label {
            font-weight: 500;
        }
        
        .input-group {
            gap: 10px;
        }
        
        .table th {
            font-weight: 600;
        }
        
        .btn-sm {
            margin: 0 2px;
        }
        
        .filter-popup {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            width: min(800px, 95vw);
            max-height: 90vh;
            overflow-y: auto;
            right: 50%;
            transform: translateX(50%);
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            z-index: 1000;
            padding: 1rem;
        }
        
        .filter-container {
            position: relative;
        }
        
        .filter-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .container {
            max-width: 95% !important;
            margin: 0 auto;
        }
        
        .table {
            width: 100%;
            font-size: 14px;
        }
        
        .table th, .table td {
            padding: 12px 8px;
            vertical-align: middle;
            white-space: nowrap;
        }
        
        .table th:first-child,
        .table td:first-child {
            min-width: 120px;
        }
        
        .table th:nth-child(2),
        .table td:nth-child(2) {
            min-width: 100px;
        }
        
        .table th:nth-child(8),
        .table td:nth-child(8),
        .table th:nth-child(9),
        .table td:nth-child(9) {
            min-width: 160px;
        }
        
        .table-responsive {
            overflow-x: auto;
            margin: 0 -15px;
            padding: 0 15px;
        }
        
        .btn-group-action {
            display: flex;
            gap: 4px;
            justify-content: flex-start;
        }
        
        @media (max-width: 768px) {
            .filter-popup {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 95vw;
                height: 90vh;
                z-index: 1050;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .input-group {
                flex-direction: column;
                gap: 5px;
            }
            
            .input-group > * {
                width: 100% !important;
            }
            
            .btn-group-action {
                flex-direction: column;
                gap: 5px;
            }
            
            .btn-group-action .btn {
                width: 100%;
            }
            
            /* Adjust header layout for mobile */
            .card-body.d-flex {
                flex-direction: column;
                gap: 10px;
            }
            
            .d-flex.align-items-center.gap-3 {
                flex-direction: column;
                width: 100%;
            }
            
            .search-container {
                width: 100%;
            }
            
            .search-container input {
                width: 100% !important;
            }
            
            .filter-container {
                width: 100%;
            }
            
            .filter-container button {
                width: 100%;
            }
            
            /* Make "Add New Order" button full width on mobile */
            .btn.btn-success {
                width: 100%;
            }
            
            /* Adjust form layouts */
            .row.mb-3 {
                margin: 0;
            }
            
            .col-md-4, .col-md-6 {
                padding: 5px;
            }
            
            /* Adjust modal for better mobile view */
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .modal-body {
                padding: 1rem 0.5rem;
            }
            
            /* Improve table responsiveness */
            .table-responsive {
                margin: 0;
                padding: 0;
            }
            
            .table {
                font-size: 12px;
            }
            
            .table th, .table td {
                padding: 8px 4px;
            }
        }
        
        /* Remove the dark mode media query and add light theme specific styles */
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        
        .card {
            background-color: #ffffff;
            border-color: #dee2e6;
        }
        
        .table {
            color: #212529;
        }
        
        .modal-content {
            background-color: #ffffff;
            color: #212529;
        }
        
        .form-control {
            background-color: #ffffff;
            border-color: #ced4da;
            color: #212529;
        }
        
        .form-control:focus {
            background-color: #ffffff;
            color: #212529;
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .filter-popup {
            background-color: #ffffff;
            border-color: #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Amazon Delivery Management</h1>
        
        <!-- Search Form -->
        <div class="card mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Orders List</h5>
                <div class="d-flex align-items-center gap-3">
                    <div class="search-container">
                        <input type="text" 
                               class="form-control" 
                               id="globalSearch" 
                               placeholder="Search across all columns..."
                               style="width: 300px;">
                    </div>
                    <div class="filter-container">
                        <button type="button" class="btn btn-primary" id="filterButton">
                            <i class="fas fa-filter"></i> Filters
                            <span class="filter-badge d-none" id="filterCount">0</span>
                        </button>
                        <div class="filter-popup" id="filterPopup">
                            <form id="searchForm" class="row g-3">
                                <!-- Basic Info Section -->
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Order ID</label>
                                            <input type="text" class="form-control" id="order_id" placeholder="Enter Order ID">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Category</label>
                                            <select class="form-control" id="category_id">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Area</label>
                                            <select class="form-control" id="area_id">
                                                <option value="">Select Area</option>
                                                @foreach($areas as $area)
                                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Time Filters Section -->
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Order Date Range</label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="date_from" placeholder="From">
                                                <input type="date" class="form-control" id="date_to" placeholder="To">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Order Time Range</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="order_time_from" placeholder="From">
                                                <input type="time" class="form-control" id="order_time_to" placeholder="To">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Pickup Time Range</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="pickup_time_from" placeholder="From">
                                                <input type="time" class="form-control" id="pickup_time_to" placeholder="To">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delivery Conditions Section -->
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Vehicle Type</label>
                                            <select class="form-control" id="vehicle_type">
                                                <option value="">Select Vehicle</option>
                                                @foreach($vehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}">{{ ucfirst($vehicle->type) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Traffic Condition</label>
                                            <select class="form-control" id="traffic_id">
                                                <option value="">Select Traffic</option>
                                                @foreach($trafficConditions as $traffic)
                                                    <option value="{{ $traffic->id }}">{{ $traffic->level }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Agent Rating</label>
                                            <input type="number" class="form-control" id="rating" placeholder="Minimum Rating" min="1" max="5" step="0.1">
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Filters Section -->
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Store Location Range</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="store_lat_from" placeholder="Min Latitude" step="0.000001">
                                                <input type="number" class="form-control" id="store_lat_to" placeholder="Max Latitude" step="0.000001">
                                                <input type="number" class="form-control" id="store_long_from" placeholder="Min Longitude" step="0.000001">
                                                <input type="number" class="form-control" id="store_long_to" placeholder="Max Longitude" step="0.000001">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Drop Location Range</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="drop_lat_from" placeholder="Min Latitude" step="0.000001">
                                                <input type="number" class="form-control" id="drop_lat_to" placeholder="Max Latitude" step="0.000001">
                                                <input type="number" class="form-control" id="drop_long_from" placeholder="Min Longitude" step="0.000001">
                                                <input type="number" class="form-control" id="drop_long_to" placeholder="Max Longitude" step="0.000001">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add these inside the filter form in the filter popup -->
                                <div class="col-md-4">
                                    <label class="form-label">Weather Condition</label>
                                    <select class="form-control" id="weather_id">
                                        <option value="">Select Weather</option>
                                        @foreach($weatherConditions as $weather)
                                            <option value="{{ $weather->id }}">{{ $weather->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Agent Age Range</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="agent_age_from" placeholder="From">
                                        <input type="number" class="form-control" id="agent_age_to" placeholder="To">
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    <button type="button" class="btn btn-secondary" id="clearFilters">Clear All</button>
                                    <button type="button" class="btn btn-light" id="closeFilters">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                    Add New Order
                </button>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="ordersTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Agent</th>
                                <th>Vehicle</th>
                                <th>Category</th>
                                <th>Area</th>
                                <th>Order Date</th>
                                <th>Order Time</th>
                                <th>Pickup Time</th>
                                <th>Store Location</th>
                                <th>Drop Location</th>
                                <th>Weather</th>
                                <th>Traffic</th>
                                <th>Delivery Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Order Modal -->
    <div class="modal fade" id="addOrderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addOrderForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Order ID</label>
                                <input type="text" class="form-control" name="order_id" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-control" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Agent Details -->
                            <div class="col-md-4">
                                <label class="form-label">Agent Age</label>
                                <input type="number" class="form-control" name="agent_age" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Agent Rating</label>
                                <input type="number" step="0.1" class="form-control" name="agent_rating" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Vehicle Type</label>
                                <select class="form-control" name="vehicle_type" required>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Location Details -->
                            <div class="col-md-6">
                                <label class="form-label">Store Location</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="store_latitude" placeholder="Latitude" required>
                                    <input type="text" class="form-control" name="store_longitude" placeholder="Longitude" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Drop Location</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="drop_latitude" placeholder="Latitude" required>
                                    <input type="text" class="form-control" name="drop_longitude" placeholder="Longitude" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Area</label>
                                <select class="form-control" name="area_id" required>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Order Details -->
                            <div class="col-md-4">
                                <label class="form-label">Order Date</label>
                                <input type="date" class="form-control" name="order_date" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Order Time</label>
                                <input type="time" class="form-control" name="order_time" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pickup Time</label>
                                <input type="time" class="form-control" name="pickup_time" required>
                            </div>
                            <!-- Conditions -->
                            <div class="col-md-4">
                                <label class="form-label">Weather</label>
                                <select class="form-control" name="weather_id" required>
                                    @foreach($weatherConditions as $weather)
                                        <option value="{{ $weather->id }}">{{ $weather->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Traffic</label>
                                <select class="form-control" name="traffic_id" required>
                                    @foreach($trafficConditions as $traffic)
                                        <option value="{{ $traffic->id }}">{{ $traffic->level }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Delivery Time (minutes)</label>
                                <input type="number" class="form-control" name="delivery_time" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Save Order</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Order Modal -->
    <div class="modal fade" id="editOrderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editOrderForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Order ID</label>
                                <input type="text" class="form-control" name="order_id" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-control" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Agent Details -->
                            <div class="col-md-4">
                                <label class="form-label">Agent Age</label>
                                <input type="number" class="form-control" name="agent_age" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Agent Rating</label>
                                <input type="number" step="0.1" class="form-control" name="agent_rating" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Vehicle Type</label>
                                <select class="form-control" name="vehicle_type" required>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Location Details -->
                            <div class="col-md-6">
                                <label class="form-label">Store Location</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="store_latitude" placeholder="Latitude" required>
                                    <input type="text" class="form-control" name="store_longitude" placeholder="Longitude" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Drop Location</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="drop_latitude" placeholder="Latitude" required>
                                    <input type="text" class="form-control" name="drop_longitude" placeholder="Longitude" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Area</label>
                                <select class="form-control" name="area_id" required>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Order Details -->
                            <div class="col-md-4">
                                <label class="form-label">Order Date</label>
                                <input type="date" class="form-control" name="order_date" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Order Time</label>
                                <input type="time" class="form-control" name="order_time" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pickup Time</label>
                                <input type="time" class="form-control" name="pickup_time" required>
                            </div>
                            <!-- Conditions -->
                            <div class="col-md-4">
                                <label class="form-label">Weather</label>
                                <select class="form-control" name="weather_id" required>
                                    @foreach($weatherConditions as $weather)
                                        <option value="{{ $weather->id }}">{{ $weather->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Traffic</label>
                                <select class="form-control" name="traffic_id" required>
                                    @foreach($trafficConditions as $traffic)
                                        <option value="{{ $traffic->id }}">{{ $traffic->level }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Delivery Time (minutes)</label>
                                <input type="number" class="form-control" name="delivery_time" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Save Order</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            const table = $('#ordersTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                dom: '<"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                language: {
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    lengthMenu: "Show _MENU_ entries",
                    processing: "Loading...",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                ajax: {
                    url: '/api/v1/orders',
                    data: function(d) {
                        // Remove the default search parameter
                        delete d.search;
                        
                        // Add our custom global search
                        d.global_search = $('#globalSearch').val();
                        
                        // Keep other filter parameters
                        d.order_id = $('#order_id').val();
                        d.category_id = $('#category_id').val();
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                        d.vehicle_type = $('#vehicle_type').val();
                        d.traffic_id = $('#traffic_id').val();
                        d.rating = $('#rating').val();
                        d.area_id = $('#area_id').val();
                        
                        // Add new time range parameters
                        d.order_time_from = $('#order_time_from').val();
                        d.order_time_to = $('#order_time_to').val();
                        d.pickup_time_from = $('#pickup_time_from').val();
                        d.pickup_time_to = $('#pickup_time_to').val();
                        
                        // Add location range parameters
                        d.store_lat_from = $('#store_lat_from').val();
                        d.store_lat_to = $('#store_lat_to').val();
                        d.store_long_from = $('#store_long_from').val();
                        d.store_long_to = $('#store_long_to').val();
                        d.drop_lat_from = $('#drop_lat_from').val();
                        d.drop_lat_to = $('#drop_lat_to').val();
                        d.drop_long_from = $('#drop_long_from').val();
                        d.drop_long_to = $('#drop_long_to').val();
                        
                        // Add new filter parameters
                        d.weather_id = $('#weather_id').val();
                        d.agent_age_from = $('#agent_age_from').val();
                        d.agent_age_to = $('#agent_age_to').val();
                    }
                },
                columns: [
                    { data: 'order_id' },
                    { 
                        data: 'agent',
                        render: function(data) {
                            return data ? `Age: ${data.age}<br>Rating: ${data.rating}` : '';
                        }
                    },
                    {
                        data: 'agent',
                        render: function(data) {
                            return data && data.vehicle ? data.vehicle.type : '';
                        }
                    },
                    { 
                        data: 'category',
                        render: function(data) {
                            return data ? data.name : '';
                        }
                    },
                    {
                        data: 'store_location',
                        render: function(data) {
                            return data && data.area ? data.area.name : '';
                        }
                    },
                    { data: 'order_date' },
                    { data: 'order_time' },
                    { data: 'pickup_time' },
                    { 
                        data: 'store_location',
                        render: function(data) {
                            return data ? `${data.latitude}, ${data.longitude}` : '';
                        }
                    },
                    { 
                        data: 'drop_location',
                        render: function(data) {
                            return data ? `${data.latitude}, ${data.longitude}` : '';
                        }
                    },
                    {
                        data: 'weather',
                        render: function(data) {
                            return data ? data.name : '';
                        }
                    },
                    {
                        data: 'traffic',
                        render: function(data) {
                            return data ? data.level : '';
                        }
                    },
                    { data: 'delivery_time' },
                    {
                        data: 'id',
                        render: function(data) {
                            return `
                                <button class="btn btn-sm btn-primary edit-btn" data-id="${data}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${data}">Delete</button>
                            `;
                        },
                        orderable: false
                    }
                ],
                order: [[0, 'asc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                columnDefs: [
                    {
                        targets: [8, 9, 10, 11], // Hide less important columns on mobile
                        className: 'd-none d-md-table-cell'
                    }
                ],
            });

            // Add window resize handler to adjust table columns
            $(window).on('resize', function() {
                table.columns.adjust();
            });

            // Add global search handler with debounce
            let searchTimeout;
            $('#globalSearch').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    table.draw();
                }, 500); // Wait 500ms after user stops typing before searching
            });

            // Filter popup handling
            const filterButton = $('#filterButton');
            const filterPopup = $('#filterPopup');
            const filterCount = $('#filterCount');
            const closeFilters = $('#closeFilters');
            
            filterButton.on('click', function(e) {
                e.stopPropagation();
                filterPopup.toggle();
            });
            
            closeFilters.on('click', function() {
                filterPopup.hide();
            });
            
            // Close popup when clicking outside
            $(document).on('click', function(e) {
                if (!filterPopup.is(e.target) && 
                    filterPopup.has(e.target).length === 0 && 
                    !filterButton.is(e.target)) {
                    filterPopup.hide();
                }
            });
            
            // Update filter count badge
            function updateFilterCount() {
                const filledInputs = $('#searchForm').find('input, select').filter(function() {
                    return $(this).val() !== '' && $(this).val() !== null;
                }).length;
                
                if (filledInputs > 0) {
                    filterCount.text(filledInputs).removeClass('d-none');
                } else {
                    filterCount.addClass('d-none');
                }
            }
            
            // Monitor filter changes
            $('#searchForm').find('input, select').on('change', updateFilterCount);
            
            // Update count when clearing filters
            $('#clearFilters').on('click', function() {
                $('#searchForm').trigger('reset');
                updateFilterCount();
                table.draw();
            });
            
            // Handle form submission
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                updateFilterCount();
                table.draw();
                filterPopup.hide();
            });

            // Delete Order
            $('#ordersTable').on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this order?')) {
                    $.ajax({
                        url: `/api/v1/orders/${id}`,
                        method: 'DELETE',
                        success: function() {
                            table.ajax.reload();
                        }
                    });
                }
            });

            // Add Order Form Submit
            $('#addOrderForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                $.ajax({
                    url: '/api/v1/orders',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#addOrderModal').modal('hide');
                        table.ajax.reload();
                        alert('Order created successfully!');
                    },
                    error: function(xhr) {
                        alert('Error creating order: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Edit Order
            $('#ordersTable').on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                
                // Fetch order details
                $.get(`/api/v1/orders/${id}`, function(order) {
                    // Populate edit form with all fields
                    $('#editOrderForm [name="order_id"]').val(order.order_id);
                    $('#editOrderForm [name="category_id"]').val(order.category_id);
                    $('#editOrderForm [name="agent_age"]').val(order.agent.age);
                    $('#editOrderForm [name="agent_rating"]').val(order.agent.rating);
                    $('#editOrderForm [name="vehicle_type"]').val(order.agent.vehicle_id);
                    $('#editOrderForm [name="store_latitude"]').val(order.store_location.latitude);
                    $('#editOrderForm [name="store_longitude"]').val(order.store_location.longitude);
                    $('#editOrderForm [name="drop_latitude"]').val(order.drop_location.latitude);
                    $('#editOrderForm [name="drop_longitude"]').val(order.drop_location.longitude);
                    $('#editOrderForm [name="area_id"]').val(order.store_location.area_id);
                    $('#editOrderForm [name="order_date"]').val(order.order_date);
                    $('#editOrderForm [name="order_time"]').val(order.order_time);
                    $('#editOrderForm [name="pickup_time"]').val(order.pickup_time);
                    $('#editOrderForm [name="weather_id"]').val(order.weather_id);
                    $('#editOrderForm [name="traffic_id"]').val(order.traffic_id);
                    $('#editOrderForm [name="delivery_time"]').val(order.delivery_time);
                    
                    // Store the order ID for the form submission
                    $('#editOrderForm').data('order-id', order.id);
                    
                    $('#editOrderModal').modal('show');
                });
            });

            // Edit Order Form Submit
            $('#editOrderForm').on('submit', function(e) {
                e.preventDefault();
                const id = $(this).data('order-id');
                const formData = new FormData(this);
                
                // Add _method field to simulate PUT request
                formData.append('_method', 'PUT');
                
                $.ajax({
                    url: `/api/v1/orders/${id}`,
                    method: 'POST', // Keep this as POST, the _method field will handle the routing
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#editOrderModal').modal('hide');
                        table.ajax.reload();
                        alert('Order updated successfully!');
                    },
                    error: function(xhr) {
                        alert('Error updating order: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Clear form when Add Order modal is opened
            $('#addOrderModal').on('show.bs.modal', function() {
                $('#addOrderForm').trigger('reset');
            });

            // Add cleanup when edit modal is hidden
            $('#editOrderModal').on('hidden.bs.modal', function() {
                $('#editOrderForm').removeData('order-id');
            });

            // Add cleanup when add modal is hidden
            $('#addOrderModal').on('hidden.bs.modal', function() {
                $('#addOrderForm').trigger('reset');
            });

            // Add orientation change handler
            $(window).on('orientationchange', function() {
                table.columns.adjust().responsive.recalc();
            });
        });
    </script>
</body>
</html> 