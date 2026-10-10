<?php

namespace App\Livewire;

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

        // The component state can be stale (another tab, the employee's own timer),
        // so the user model checks the database before starting or stopping.
        if ($this->isRunning) {
            $this->employee->stopTimer();
        } else {
            $this->employee->startTimer();
        }

        $this->isRunning = $this->employee->hasActiveTimeLog();
    }

    public function render()
    {
        return view('livewire.employee-timer');
    }
}
