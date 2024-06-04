<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Acrylic', 'parent_uuid' => null, 'description' => 'Acrylic description'],
            ['name' => 'Letter press', 'parent_uuid' => null, 'description' => 'Letter press description'],
            ['name' => 'Luxury', 'parent_uuid' => null, 'description' => 'Luxury description'],
            ['name' => 'Laser cut ', 'parent_uuid' => 1, 'description' => 'Laser cut description'],
            ['name' => 'Pocket', 'parent_uuid' => 3, 'description' => 'Pocket description'],
            ['name' => 'Basic', 'parent_uuid' => 1, 'description' => 'Basic description'],
            ['name' => 'Vintage', 'parent_uuid' => 1, 'description' => 'Vintage description']
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}