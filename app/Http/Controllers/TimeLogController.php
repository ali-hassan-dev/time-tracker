<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimeLogController extends Controller
{
    public function index()
    {
        $timeLogs = Auth::user()->isAdmin() ? TimeLog::with('user')->get() : Auth::user()->timeLogs;
        return view('timelogs.index', compact('timeLogs'));
    }

    public function start()
    {
        $timeLog = TimeLog::create([
            'user_id' => Auth::id(),
            'start_time' => Carbon::now(),
        ]);

        return response()->json($timeLog, 201);
    }

    public function stop($id)
    {
        $timeLog = TimeLog::where('user_id', Auth::id())->whereNull('end_time')->findOrFail($id);
        $timeLog->update(['end_time' => Carbon::now()]);

        return response()->json($timeLog, 200);
    }
}
