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
        $products = [
            [
                'name' => 'Arcrylic',
                'description' => 'Arcrylic description',
                'price' => 1000,
                'category_uuid' => $this->categoryService->findOneWhere(['name' => 'Acrylic'])->uuid,
                'image' => 'https://apis.book-invitation.encacap.com/images/systems/1714704529.jpg',
                'detail_images' => "[\"https:\\/\\/apis.book-invitation.encacap.com\\/images\\/systems\\/1714704529.jpg\",\"https:\\/\\/apis.book-invitation.encacap.com\\/images\\/systems\\/1714704529.jpg\"]",
            ],
            [
                'name' => 'Letter press',
                'description' => 'Letter press description',
                'price' => 2000,
                'category_uuid' => $this->categoryService->findOneWhere(['name' => 'Letter press'])->uuid,
                'image' => 'https://apis.book-invitation.encacap.com/images/systems/1714704539.jpg',
                'detail_images' => "[\"https:\\/\\/apis.book-invitation.encacap.com\\/images\\/systems\\/1714704539.jpg\",\"https:\\/\\/apis.book-invitation.encacap.com\\/images\\/systems\\/1714704539.jpg\"]",
            ],
            [
                'name' => 'Luxury',
                'description' => 'Luxury description',
                'price' => 1000,
                'category_uuid' => $this->categoryService->findOneWhere(['name' => 'Luxury'])->uuid,
                'image' => 'https://apis.book-invitation.encacap.com/images/systems/1714704561.jpg',
                'detail_images' => "[\"https:\\/\\/apis.book-invitation.encacap.com\\/images\\/systems\\/1714704561.jpg\",\"https:\\/\\/apis.book-invitation.encacap.com\\/images\\/systems\\/1714704561.jpg\"]",
            ],
            [
                'name' => 'Basic',
                'description' => 'Basic description',
                'price' => 3000,
                'category_uuid' => $this->categoryService->findOneWhere(['name' => 'Basic'])->uuid,
                'image' => 'https://apis.book-invitation.encacap.com/images/systems/1714704570.jpg',
                'detail_images' => "[\"https:\\/\\/apis.book-invitation.encacap.com\\/images\\/systems\\/1714704570.jpg\",\"https:\\/\\/apis.book-invitation.encacap.com\\/images\\/systems\\/1714704570.jpg\"]",
            ]
        ];


        foreach ($products as $product) {
            Product::updateOrCreate(['name' => $product['name'], 'category_uuid' => $product['category_uuid'], 'image' => $product['image']], $product);
        }
    }
}