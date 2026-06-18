<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Seed Client user
        User::factory()->create([
            'name' => 'James Mitchell',
            'email' => 'client@example.com',
            'password' => bcrypt('password'),
            'organisation' => 'Acme Corporation',
            'account_type' => 'client',
        ]);

        // Seed Partner user
        User::factory()->create([
            'name' => 'Sarah Jenkins',
            'email' => 'partner@example.com',
            'password' => bcrypt('password'),
            'organisation' => 'Globex Logistics',
            'account_type' => 'partner',
        ]);

        // Seed Admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@enterpriseit.com.au',
            'password' => bcrypt('password'),
            'organisation' => 'enterprise IT',
            'account_type' => 'admin',
        ]);

        $this->call(ResourceSeeder::class);
    }
}
