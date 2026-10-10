<?php

use App\Traits\FormatsDuration;

beforeEach(function () {
    $this->formatter = new class
    {
        use FormatsDuration;
    };
});

test('durations use singular and plural units', function (int|float $seconds, string $expected) {
    expect($this->formatter->formatDuration($seconds))->toBe($expected);
})->with([
    [0, '0 seconds'],
    [1, '1 second'],
    [45, '45 seconds'],
    [60, '1 minute'],
    [150, '2 minutes'],
    [3600, '1 hour'],
    [3660, '1 hour 1 minute'],
    [5400, '1 hour 30 minutes'],
    [7200, '2 hours'],
    [8100, '2 hours 15 minutes'],
    [3600.0, '1 hour'],
]);

test('whole hours have no trailing space', function () {
    expect($this->formatter->formatDuration(7200))->not->toEndWith(' ');
});
