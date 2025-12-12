<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        if (!Admin::where('email', 'admin')->first()) {
            Admin::create([
                'name' => 'Admin',
                'email' => 'admin',
                'password' => Hash::make('fyp2025'),
            ]);
        }
    }
}
