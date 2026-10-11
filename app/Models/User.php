<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Role;
use App\Traits\FormatsDuration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use FormatsDuration, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'username',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    public function isEmployee(): bool
    {
        return $this->role === Role::Employee;
    }

    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class);
    }

    public function getTotalTrackedTimeAttribute()
    {
        $totalSeconds = $this->timeLogs->reduce(function ($carry, $timeLog) {
            return $carry + $this->calculateDuration($timeLog->start_time, $timeLog->end_time);
        }, 0);

        return $this->formatDuration($totalSeconds);
    }

    public function hasActiveTimeLog()
    {
        return TimeLog::hasActiveTimeLog($this->id);
    }

    /**
     * Start a timer, unless one is already running (for example in another tab).
     */
    public function startTimer(): void
    {
        if (! $this->hasActiveTimeLog()) {
            $this->timeLogs()->create([
                'start_time' => now(),
                'date' => now()->toDateString(),
            ]);
        }
    }

    /**
     * Stop every running timer for this user.
     */
    public function stopTimer(): void
    {
        $this->timeLogs()->whereNull('end_time')->update(['end_time' => now()]);
    }
}
