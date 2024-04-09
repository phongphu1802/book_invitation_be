<?php

namespace Database\Seeders;

use App\Services\CategoryService;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create(['name' => 'Product 1', 
                        'description' => 'Product 1 description',
                        'price' => 1000,
                        'category_uuid' => $this->categoryService->findOneWhere(['name' => 'Mailbox'])->uuid,
                        'image' => 'images',
                        ]);
        Product::create(['name' => 'Product 2', 
                        'description' => 'Product 2 description',
                        'price' => 2000,
                        'category_uuid' => $this->categoryService->findOneWhere(['name' => 'SMS'])->uuid,
                        'image' => 'images',
                        ]);
        Product::create(['name' => 'Product 3', 
                        'description' => 'Product 3 description',
                        'price' => 3000,
                        'category_uuid' => $this->categoryService->findOneWhere(['name' => 'Email'])->uuid,
                        'image' => 'images',
                        ]);
    }
}
