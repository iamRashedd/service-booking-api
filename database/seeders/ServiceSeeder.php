<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        
        $car = Category::where('name','Car')->first() ?? Category::first();
        Service::create([
            'name' => 'Car-Service-1',
            'category_id' => $car->id,
            'price' => 500,
        ]);
        $home = Category::where('name','Home')->first() ?? Category::first();
        Service::create([
            'name' => 'Home-Clean-1',
            'category_id' => $home->id,
            'price' => 1000,
        ]);
        
        Service::create([
            'name' => 'Home-Clean-2',
            'category_id' => $home->id,
            'price' => 2200,
        ]);
    }
}
