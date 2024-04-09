<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Enums\RoleEnum;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => RoleEnum::ADMIN]);
        Role::create(['name' => RoleEnum::SYSTEM]);
        Role::create(['name' => RoleEnum::USER]);
    }
}
