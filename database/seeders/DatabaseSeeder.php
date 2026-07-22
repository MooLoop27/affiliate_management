<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default users
        $owner = User::create([
            'name' => 'System Owner',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567891',
            'is_active' => true,
        ]);

        $finance = User::create([
            'name' => 'Finance User',
            'email' => 'finance@example.com',
            'password' => Hash::make('password'),
            'role' => 'finance',
            'phone' => '081234567892',
            'is_active' => true,
        ]);

        // Create default settings
        $defaultSettings = [
            ['key' => 'company_name', 'value' => 'Affiliate Commission System', 'group' => 'general'],
            ['key' => 'sg_commission_percentage', 'value' => '5', 'group' => 'general'],
            ['key' => 'leader_commission_percentage', 'value' => '10', 'group' => 'general'],
            ['key' => 'default_recipient_commission_percentage', 'value' => '2', 'group' => 'general'],
            ['key' => 'system_theme', 'value' => 'light', 'group' => 'general'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::create($setting);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Owner: owner@example.com / password');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Finance: finance@example.com / password');
    }
}

