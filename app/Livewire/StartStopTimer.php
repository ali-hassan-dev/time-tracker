<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StartStopTimer extends Component
{
    public $isRunning;

    public function mount()
    {
        $this->isRunning = Auth::user()->hasActiveTimeLog();
    }

    public function toggleTimer()
    {
        $user = Auth::user();

        // The component state can be stale (another tab, a double click), so the
        // user model checks the database before starting or stopping.
        if ($this->isRunning) {
            $user->stopTimer();
        } else {
            $user->startTimer();
        }

        $this->isRunning = $user->hasActiveTimeLog();
    }

    public function render()
    {
        return view('livewire.start-stop-timer');
    }
}
