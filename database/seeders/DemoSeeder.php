<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Country;
use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Fake data for trying the app locally: one admin, four employees, and about
 * 60 time logs over the last two weeks. Every account uses the password
 * "password". Running it again replaces the demo employees' logs.
 */
class DemoSeeder extends Seeder
{
    private const COUNTRIES = [
        'Canada' => 'CAN',
        'Germany' => 'DEU',
        'Philippines' => 'PHL',
        'Brazil' => 'BRA',
    ];

    private const CLIENTS = ['Acme Corp', 'Globex', 'Initech', 'Northwind Traders', 'Contoso'];

    private const EMPLOYEES = [
        ['name' => 'Alex Example', 'username' => 'alex'],
        ['name' => 'Blair Sample', 'username' => 'blair'],
        ['name' => 'Casey Demo', 'username' => 'casey'],
        ['name' => 'Drew Placeholder', 'username' => 'drew'],
    ];

    private const LOGS_PER_EMPLOYEE = 15;

    public function run(): void
    {
        fake()->seed(2024);

        foreach (self::COUNTRIES as $name => $code) {
            Country::firstOrCreate(['code' => $code], ['name' => $name]);
        }

        $this->demoUser('Demo Admin', 'admin', Role::Admin);

        foreach (self::EMPLOYEES as $index => $employee) {
            $user = $this->demoUser($employee['name'], $employee['username'], Role::Employee);

            $user->timeLogs()->delete();
            $this->createLogs($user);

            // The first employee is on a session right now, so the timers show both states.
            if ($index === 0) {
                $user->timeLogs()->create([
                    'start_time' => now()->subMinutes(42),
                    'login_time' => now()->subMinutes(45),
                    'talk_type' => 'Talk',
                    'client' => self::CLIENTS[0],
                    'date' => now()->toDateString(),
                    'country' => 'Canada',
                    'payment_method' => 'Phone',
                ]);
            }
        }
    }

    private function demoUser(string $name, string $username, Role $role): User
    {
        $user = User::firstOrNew(['email' => "{$username}@example.com"]);
        $user->name = $name;
        $user->username = $username;
        $user->password = Hash::make('password');
        $user->role = $role;
        $user->email_verified_at = now();
        $user->save();

        return $user;
    }

    private function createLogs(User $user): void
    {
        // Spread the sessions over the last 14 days, skipping today.
        $days = array_merge(
            fake()->randomElements(range(1, 14), self::LOGS_PER_EMPLOYEE - 3),
            fake()->randomElements(range(1, 14), 3),
        );

        foreach ($days as $daysAgo) {
            $start = now()->subDays($daysAgo)->setTime(fake()->numberBetween(8, 16), fake()->randomElement([0, 15, 30, 45]));
            $minutes = fake()->randomElement([25, 40, 55, 75, 90, 120, 150, 180, 210, 240]);
            $end = $start->copy()->addMinutes($minutes);
            $talkTime = (int) round($minutes * fake()->randomFloat(2, 0.55, 0.9));

            TimeLog::create([
                'user_id' => $user->id,
                'login_time' => $start->copy()->subMinutes(fake()->numberBetween(2, 10)),
                'logout_time' => $end->copy()->addMinutes(fake()->numberBetween(2, 10)),
                'start_time' => $start,
                'end_time' => $end,
                'talk_type' => fake()->randomElement(['Chat', 'Talk']),
                'talk_time' => $talkTime,
                'client' => fake()->randomElement(self::CLIENTS),
                'date' => $start->toDateString(),
                'payout' => round($talkTime * fake()->randomFloat(2, 0.4, 0.8), 2),
                'country' => fake()->randomElement(array_keys(self::COUNTRIES)),
                'payment_method' => fake()->randomElement(['Phone', 'Crypto']),
            ]);
        }
    }
}
