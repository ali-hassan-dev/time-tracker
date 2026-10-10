<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $timeLogs = TimeLog::with('user')
            ->visibleTo($user)
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('start_time', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('start_time', '<=', $request->date_to))
            ->when($request->filled('talk_type'), fn ($query) => $query->where('talk_type', $request->talk_type))
            ->when($request->filled('client'), fn ($query) => $query->where('client', 'like', '%'.$request->client.'%'))
            ->when($request->filled('country'), fn ($query) => $query->where('country', 'like', '%'.$request->country.'%'))
            ->when($request->filled('payment_method'), fn ($query) => $query->where('payment_method', $request->payment_method))
            ->orderBy('start_time', 'desc')
            ->paginate(25)
            ->withQueryString();

        $countries = TimeLog::visibleTo($user)->select('country')->whereNotNull('country')->distinct()->pluck('country');

        return view('timelogs.index', compact('timeLogs', 'countries'));
    }
}
