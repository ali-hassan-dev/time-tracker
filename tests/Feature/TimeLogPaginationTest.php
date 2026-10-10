<?php

use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->employee = User::factory()->create();

    TimeLog::factory()
        ->count(30)
        ->for($this->employee)
        ->sequence(fn ($sequence) => ['start_time' => Carbon::parse('2024-06-01 08:00:00')->addHours($sequence->index)])
        ->create();
});

test('time logs are paginated 25 per page', function () {
    $response = $this->actingAs($this->admin)->get('/timelogs');

    $timeLogs = $response->viewData('timeLogs');

    expect($timeLogs->count())->toBe(25)
        ->and($timeLogs->total())->toBe(30);

    $this->actingAs($this->admin)
        ->get('/timelogs?page=2')
        ->assertViewHas('timeLogs', fn ($page) => $page->count() === 5);
});

test('pagination links keep the active filters', function () {
    $this->actingAs($this->admin)
        ->get('/timelogs?date_from=2024-06-01&date_to=2024-06-30')
        ->assertSee('date_from=2024-06-01&date_to=2024-06-30&page=2');
});

test('row numbers continue on the next page', function () {
    $this->actingAs($this->admin)
        ->get('/timelogs?page=2')
        ->assertSeeInOrder(['<td class="py-3 px-5 border-b text-center">26</td>'], false);
});

test('an employee\'s logs are paginated on the admin page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.users.time-logs.index', $this->employee))
        ->assertOk()
        ->assertViewHas('timeLogs', fn ($page) => $page->count() === 25 && $page->total() === 30);
});
