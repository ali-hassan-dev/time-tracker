<?php

use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();

    $log = fn (string $start, ?string $end) => TimeLog::factory()->create([
        'start_time' => Carbon::parse($start),
        'end_time' => $end ? Carbon::parse($end) : null,
    ]);

    $log('2024-05-31 23:30:00', '2024-06-01 00:30:00'); // before the range
    $log('2024-06-01 09:00:00', '2024-06-01 11:00:00'); // first day
    $log('2024-06-03 08:00:00', '2024-06-03 09:00:00'); // last day, morning
    $log('2024-06-03 22:00:00', '2024-06-03 23:30:00'); // last day, evening
    $log('2024-06-03 18:00:00', null);                  // running, started on the last day
    $log('2024-06-04 00:00:00', '2024-06-04 01:00:00'); // after the range
});

test('date filters include every log that starts within the range', function () {
    $this->actingAs($this->admin)
        ->get('/timelogs?date_from=2024-06-01&date_to=2024-06-03')
        ->assertOk()
        ->assertSee('2024-06-01 09:00:00')
        ->assertSee('2024-06-03 08:00:00')
        ->assertSee('2024-06-03 22:00:00')
        ->assertSee('2024-06-03 18:00:00')
        ->assertDontSee('2024-05-31 23:30:00')
        ->assertDontSee('2024-06-04 00:00:00');
});

test('date_to alone includes logs that start on that day', function () {
    $this->actingAs($this->admin)
        ->get('/timelogs?date_to=2024-06-03')
        ->assertSee('2024-05-31 23:30:00')
        ->assertSee('2024-06-03 22:00:00')
        ->assertSee('2024-06-03 18:00:00')
        ->assertDontSee('2024-06-04 00:00:00');
});

test('empty filters are ignored', function () {
    $this->actingAs($this->admin)
        ->get('/timelogs?date_from=&date_to=&talk_type=&client=&country=&payment_method=')
        ->assertOk()
        ->assertSee('2024-05-31 23:30:00')
        ->assertSee('2024-06-04 00:00:00');
});

test('the other filters narrow the list', function () {
    TimeLog::factory()->create([
        'start_time' => Carbon::parse('2024-07-01 10:00:00'),
        'client' => 'Filtered Client',
        'talk_type' => 'Talk',
        'country' => 'Iceland',
        'payment_method' => 'Crypto',
    ]);

    $this->actingAs($this->admin)
        ->get('/timelogs?client=Filtered&talk_type=Talk&country=Iceland&payment_method=Crypto')
        ->assertSee('2024-07-01 10:00:00')
        ->assertDontSee('2024-06-01 09:00:00');
});
