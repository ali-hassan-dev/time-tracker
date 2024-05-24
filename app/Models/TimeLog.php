<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatsDuration;

class TimeLog extends Model
{
    use HasFactory, FormatsDuration;

    protected $fillable = [
        'user_id',
        'login_time',
        'logout_time',
        'talk_type',
        'talk_time',
        'order_type',
        'date',
        'made_percentage',
        'country',
        'payment_method',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'login_time' => 'datetime',
        'logout_time' => 'datetime',
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
