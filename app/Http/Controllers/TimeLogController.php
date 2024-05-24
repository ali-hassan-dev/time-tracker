<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use Illuminate\Support\Facades\Auth;

class TimeLogController extends Controller
{
    public function index()
    {
        $timeLogs = Auth::user()->isAdmin() ? TimeLog::with('user')->get() : Auth::user()->timeLogs;
        return view('timelogs.index', compact('timeLogs'));
    }
}