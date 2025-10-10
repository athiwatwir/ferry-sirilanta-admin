<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Athiwat',
            'email' => 'athiwat.wir@gmail.com',
            'password' => 'Korn@001'
        ]);

        User::factory()->create([
            'name' => 'Super-Admin',
            'email' => 'admin@admin.com',
            'password' => 'Hello@789'
        ]);

        User::factory()->create([
            'name' => 'Sun',
            'email' => 'cheesociety@gmail.com',
            'password' => 'Hello@789'
        ]);
    }
}
