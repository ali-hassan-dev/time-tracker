<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    public function index(Request $request)
    {
        $query = TimeLog::with('user');

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->where('start_time', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->where('end_time', '<=', $request->date_to);
        }

        if ($request->has('talk_type') && !empty($request->talk_type)) {
            $query->where('talk_type', $request->talk_type);
        }

        if ($request->has('client') && !empty($request->client)) {
            $query->where('client', 'like', '%' . $request->client . '%');
        }

        if ($request->has('country') && !empty($request->country)) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        if ($request->has('payment_method') && !empty($request->payment_method)) {
            $query->where('payment_method', $request->payment_method);
        }

        $timeLogs = $query->orderBy('start_time', 'desc')->get();

        $countries = TimeLog::select('country')->whereNotNull('country')->distinct()->pluck('country');

        return view('timelogs.index', compact('timeLogs', 'countries'));
    }
}