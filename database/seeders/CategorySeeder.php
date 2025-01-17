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
        Category::create(['name' => 'MailBox', 'description' => 'Mailbox description']);
        Category::create(['name' => 'SMS', 'description' => 'SMS description']);
        Category::create(['name' => 'Email', 'description' => 'Email description']);
    }
}
