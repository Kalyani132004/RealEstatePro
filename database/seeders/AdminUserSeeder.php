<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Creates the first admin account so you can log into /admin/dashboard
     * immediately after migrating. Change this password in production!
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@realestatepro.test'],
            [
                'name' => 'RealEstatePro Admin',
                'password' => Hash::make('Admin@12345'),
                'role' => User::ROLE_ADMIN,
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now(),
            ]
        );
    }
}
