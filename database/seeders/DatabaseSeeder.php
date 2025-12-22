<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user if not exists
        $adminEmail = env('ADMIN_EMAIL', 'admin@netflow.local');
        $adminPassword = env('ADMIN_PASSWORD');

        if (!$adminPassword) {
            $this->command->error('ADMIN_PASSWORD environment variable is required!');
            $this->command->info('Set ADMIN_PASSWORD in your .env file before running the seeder.');
            return;
        }

        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => env('ADMIN_NAME', 'Administrator'),
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
            ]);

            $this->command->info("Admin user created: {$adminEmail}");
            $this->command->warn('Please change the password after first login for security!');
        } else {
            $this->command->info("Admin user already exists: {$adminEmail}");
        }
    }
}
