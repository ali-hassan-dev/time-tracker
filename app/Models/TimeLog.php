<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatsDuration;

class TimeLog extends Model
{
    use HasFactory, FormatsDuration;

    protected $fillable = ['user_id', 'start_time', 'end_time'];
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDurationAttribute()
    {
        if ($this->start_time && $this->end_time) {
            $diffInSeconds = $this->calculateDuration($this->start_time, $this->end_time);
            return $this->formatDuration($diffInSeconds);
        }
        return 'In Progress';
    }

    public static function hasActiveTimeLog($userId)
    {
        return self::where('user_id', $userId)->whereNull('end_time')->exists();
    }
}
