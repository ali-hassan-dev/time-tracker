<?php

namespace App\Traits;

use Carbon\Carbon;

trait FormatsDuration
{
    public function formatDuration($seconds)
    {
        if ($seconds < 60) {
            return $seconds . ' seconds';
        }

        $minutes = floor($seconds / 60);
        if ($minutes < 60) {
            return $minutes . ' minutes';
        }

        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;

        return $hours . ' hours ' . ($minutes > 0 ? $minutes . ' minutes' : '');
    }

    public function calculateDuration($start_time, $end_time)
    {
        if ($start_time && $end_time) {
            return Carbon::parse($start_time)->diffInSeconds(Carbon::parse($end_time));
        }
        return 0;
    }
}
