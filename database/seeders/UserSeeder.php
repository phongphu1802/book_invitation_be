<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Services\RoleService;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => RoleEnum::ADMIN, // admin
            'email' => RoleEnum::ADMIN  -> value . '@gmail.com', // admin@gmail.com
            'username' => RoleEnum::ADMIN, // admin
            'password' => bcrypt('123456'),
            'role_uuid' => $this->roleService->findOneWhere(['name' => RoleEnum::ADMIN])->uuid
        ]);

        User::create([
            'name' => RoleEnum::SYSTEM, // system
            'email' => RoleEnum::SYSTEM -> value . '@gmail.com', // system@gmail.com
            'username' => RoleEnum::SYSTEM, // system
            'password' => bcrypt('123456'),
            'role_uuid' => $this->roleService->findOneWhere(['name' => RoleEnum::SYSTEM])->uuid
        ]);

        User::create([
            'name' => RoleEnum::USER, // user
            'email' => RoleEnum::USER -> value . '@gmail.com', // user@gmail.com
            'username' => RoleEnum::USER, // user
            'password' => bcrypt('123456'),
            'role_uuid' => $this->roleService->findOneWhere(['name' => RoleEnum::USER])->uuid
        ]);
    }
}
