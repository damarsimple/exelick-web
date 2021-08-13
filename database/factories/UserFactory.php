<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $email = $this->faker->unique()->safeEmail();
        return [
            'name' => $this->faker->name(),
            'email' => $email,
            'username' => explode('@', $email)[0],
            'tag' => collect([
                'Virtual Youtuber',
                'Youtuber',
                'Professional Player',
                'Cosplayer',
                'Top Simper'
            ])->random(1)->first(),
            'description' => collect([
                '#1 Apex Predator',
                'Master Gawr Gura Simper',
                'Professional Simper',
                'Amelia Watson Fan',
            ])->random(1)->first(),
            'stream_key' => Str::random(10),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
