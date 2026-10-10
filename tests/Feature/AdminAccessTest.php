<?php

use App\Models\Country;
use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/**
 * Every /admin route, with its parameters filled in, as [method, uri] pairs.
 */
function adminRequests(): array
{
    $user = User::factory()->create();
    $timeLog = TimeLog::factory()->for($user)->create();
    $country = Country::create(['name' => 'Norway', 'code' => 'NOR']);

    return collect(Route::getRoutes()->getRoutes())
        ->filter(fn ($route) => str_starts_with($route->uri(), 'admin/'))
        ->flatMap(fn ($route) => collect($route->methods())
            ->reject(fn ($method) => $method === 'HEAD')
            ->map(fn ($method) => [
                $method,
                str_replace(['{user}', '{timeLog}', '{country}'], [$user->id, $timeLog->id, $country->id], $route->uri()),
            ]))
        ->values()
        ->all();
}

test('employees get 403 on every admin route', function () {
    $employee = User::factory()->create();
    $requests = adminRequests();

    expect($requests)->toHaveCount(19);

    foreach ($requests as [$method, $uri]) {
        $status = $this->actingAs($employee)->call($method, $uri)->status();

        expect($status)->toBe(403, "{$method} /{$uri} returned {$status}");
    }
});

test('guests are sent to the login page from every admin route', function () {
    foreach (adminRequests() as [$method, $uri]) {
        $this->call($method, $uri)->assertRedirect(route('login'));
    }
});

test('admins can open every admin page', function () {
    $admin = User::factory()->admin()->create();

    foreach (adminRequests() as [$method, $uri]) {
        if ($method === 'GET') {
            $status = $this->actingAs($admin)->get($uri)->status();

            expect($status)->toBe(200, "GET /{$uri} returned {$status}");
        }
    }
});
