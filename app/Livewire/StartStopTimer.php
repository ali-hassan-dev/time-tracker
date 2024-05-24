<?php

namespace App\Livewire;

use App\Models\TimeLog;
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
        if ($this->isRunning) {
            $activeLog = TimeLog::where('user_id', Auth::id())->whereNull('end_time')->first();
            if ($activeLog) {
                $activeLog->end_time = now();
                $activeLog->save();
            }
        } else {
            TimeLog::create([
                'user_id' => Auth::id(),
                'start_time' => now(),
            ]);
        }

        $this->isRunning = !$this->isRunning;
    }

    public function render()
    {
        return view('livewire.start-stop-timer');
    }
}
