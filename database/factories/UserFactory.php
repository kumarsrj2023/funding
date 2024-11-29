<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_login' => $this->faker->userName,
            'user_pass' => Hash::make('password'), // WordPress stores hashed passwords
            'user_nicename' => $this->faker->userName,
            'user_email' => $this->faker->unique()->safeEmail,
            'user_url' => $this->faker->url,
            'user_registered' => now(),
            'user_status' => 0, // WordPress uses 0 for active users
            'display_name' => $this->faker->name,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
