<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Role;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\FormatsDuration;

class User extends Authenticatable
{
    use HasFactory, Notifiable, FormatsDuration;

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
        'username'
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
        ];
    }

    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Role::from($value),
            set: fn (Role $value) => $value->value,
        );
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
}
