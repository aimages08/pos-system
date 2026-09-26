<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        User::updateOrCreate(
            ['email' => 'admin@pos.test'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@pos.test',
                'password' => Hash::make('admin123'),
                'role_id'  => $adminRole?->id,
                'status'   => 'active',
            ]
        );
    }
}