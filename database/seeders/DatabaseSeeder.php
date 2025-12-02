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
        if (!User::where('email', 'admin@netflow.local')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@netflow.local',
                'password' => Hash::make('admin123'),
            ]);

            $this->command->info('Admin user created: admin@netflow.local / admin123');
            $this->command->warn('Please change the default password after first login!');
        }
    }
}
