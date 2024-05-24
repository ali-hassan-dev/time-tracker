<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Http\Request;

class AdminTimeLogController extends Controller
{
    public function index(User $user)
    {
        $timeLogs = $user->timeLogs()->latest()->paginate(10);
        return view('admin.time-logs.index', compact('user', 'timeLogs'));
    }

    public function edit(User $user, TimeLog $timeLog)
    {
        return view('admin.time-logs.edit', compact('user', 'timeLog'));
    }

    public function update(Request $request, User $user, TimeLog $timeLog)
    {
        $validatedData = $request->validate([
            'login_time' => 'nullable|date',
            'logout_time' => 'nullable|date',
            'talk_type' => 'nullable|string|max:255',
            'talk_time' => 'nullable|date_format:H:i',
            'order_type' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'made_percentage' => 'nullable|numeric',
            'country' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:255',
        ]);

        $timeLog->update($validatedData);

        return redirect()->route('admin.users.time-logs.index', ['user' => $user->id])->with('success', 'Time log updated successfully.');
    }
}