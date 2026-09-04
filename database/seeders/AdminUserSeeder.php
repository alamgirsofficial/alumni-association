<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            [
                'email' => 'superadmin@alumni.com',
            ],
            [
                'name' => 'Super Admin',
                'phone' => '01700000001',
                'password' => Hash::make('super123'),
                'role' => 'super_admin',
                'status' => 'approved',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            [
                'email' => 'admin@alumni.com',
            ],
            [
                'name' => 'Admin',
                'phone' => '01700000002',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'approved',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]
        );
    }
}
