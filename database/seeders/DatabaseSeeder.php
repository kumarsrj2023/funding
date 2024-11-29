<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'user_login' => $this->faker->userName,
            'user_pass' => Hash::make('password'), // WordPress stores hashed passwords
            'user_nicename' => $this->faker->userName,
            'user_email' => $this->faker->unique()->safeEmail,
            'user_url' => $this->faker->url,
            'user_registered' => now(),
            'user_status' => 0, // WordPress uses 0 for active users
            'display_name' => $this->faker->name,
        ]);

    }
}
