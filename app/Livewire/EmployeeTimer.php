<?php

namespace App\Livewire;

use App\Models\TimeLog;
use App\Models\User;
use Livewire\Component;

class EmployeeTimer extends Component
{
    public $employee;
    public $isRunning;

    public function mount($employeeId)
    {
        $this->employee = User::findOrFail($employeeId);
        $this->isRunning = $this->employee->hasActiveTimeLog();
    }

    public function toggleTimer()
    {
        if ($this->isRunning) {
            $activeLog = $this->employee->timeLogs()->whereNull('end_time')->first();
            if ($activeLog) {
                $activeLog->end_time = now();
                $activeLog->save();
            }
        } else {
            TimeLog::create([
                'user_id' => $this->employee->id,
                'start_time' => now(),
            ]);
        }

        $this->isRunning = !$this->isRunning;
    }

    public function render()
    {
        return view('livewire.employee-timer');
    }
}
