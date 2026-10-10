<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeLog>
 */
class TimeLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::instance(fake()->dateTimeBetween('-2 weeks', '-1 day'));
        $minutes = fake()->numberBetween(15, 240);
        $end = $start->copy()->addMinutes($minutes);

        return [
            'user_id' => User::factory(),
            'login_time' => $start,
            'logout_time' => $end,
            'start_time' => $start,
            'end_time' => $end,
            'talk_type' => fake()->randomElement(['Chat', 'Talk']),
            'talk_time' => fake()->numberBetween(1, $minutes),
            'client' => fake()->company(),
            'date' => $start->toDateString(),
            'payout' => fake()->randomFloat(2, 5, 200),
            'country' => fake()->country(),
            'payment_method' => fake()->randomElement(['Phone', 'Crypto']),
        ];
    }

    /**
     * Indicate that the timer for this log is still running.
     */
    public function running(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_time' => now()->subMinutes(30),
            'end_time' => null,
            'logout_time' => null,
        ]);
    }
}
