<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->isAdmin() ? TimeLog::with('user') : Auth::user()->timeLogs();
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->where('start_time', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->where('end_time', '<=', $request->date_to);
        }

        $timeLogs = $query->orderBy('created_at', 'desc')->get();
        return view('timelogs.index', compact('timeLogs'));
    }
}