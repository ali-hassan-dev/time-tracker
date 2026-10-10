<?php

namespace App\Livewire;

use App\Models\TimeLog;
use App\Models\User;
use Livewire\Component;

class EmployeeTimer extends Component
{
    public $employee;
    public $isRunning;

    public function mount(User $employee)
    {
        $this->authorize('access-admin');

        // Use the logs the employees page already loaded instead of querying per row.
        $this->isRunning = $employee->timeLogs->contains(fn ($timeLog) => $timeLog->end_time === null);
        $this->employee = $employee->withoutRelations();
    }

    public function toggleTimer()
    {
        $this->authorize('access-admin');

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
