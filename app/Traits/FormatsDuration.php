<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait FormatsDuration
{
    public function formatDuration($seconds)
    {
        $seconds = (int) $seconds;

        if ($seconds < 60) {
            return $this->pluralizeUnit($seconds, 'second');
        }

        $minutes = intdiv($seconds, 60);
        if ($minutes < 60) {
            return $this->pluralizeUnit($minutes, 'minute');
        }

        $hours = intdiv($minutes, 60);
        $minutes = $minutes % 60;

        return trim($this->pluralizeUnit($hours, 'hour').' '.($minutes > 0 ? $this->pluralizeUnit($minutes, 'minute') : ''));
    }

    public function calculateDuration($start_time, $end_time)
    {
        if ($start_time && $end_time) {
            return Carbon::parse($start_time)->diffInSeconds(Carbon::parse($end_time));
        }

        return 0;
    }

    protected function pluralizeUnit(int $count, string $unit): string
    {
        return $count.' '.Str::plural($unit, $count);
    }
}
