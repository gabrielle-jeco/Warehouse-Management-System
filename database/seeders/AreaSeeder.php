<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run()
    {
        $areas = ['Urban', 'Metropolitian', 'Other'];
        
        foreach ($areas as $area) {
            Area::create(['name' => $area]);
        }
    }
} 