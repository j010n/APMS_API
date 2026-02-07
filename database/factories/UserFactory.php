<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
					'name' => fake()->name(),
					'email' => fake()->unique()->safeEmail(),
					'email_verified_at' => now(),
					'password' => static::$password ??= Hash::make('password'),
					'remember_token' => Str::random(10),

					'phone' => fake()->numerify('###########'), 
					'tel' => fake()->optional()->numerify('##########'),
					'cpf' => fake()->numerify('###########'),
					'rg' => fake()->numerify('#########'),
					'sex' => fake()->randomElement(['M','F','O']),
					'birthdate' => fake()->dateTimeBetween('-90 years', '-18 years')->format('Y-m-d'),

					'country' => fake()->country(),
					'state' => fake()->state(),
					'city' => fake()->city(),
					'affiliated' => fake()->boolean(30), // 30% chance de ser true
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
					'email_verified_at' => null,
        ]);
    }
}
