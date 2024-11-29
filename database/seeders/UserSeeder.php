<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1)->create([
            'user_login' => 'surajkr00',
            'user_email' => 'suraj@example.com',
            'user_pass' => Hash::make('surajkr00'), // Hash the password
            'user_nicename' => 'surajkr00',
        ]);
    }
}
