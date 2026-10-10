<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTimeLogRequest;
use App\Models\Country;
use App\Models\TimeLog;
use App\Models\User;

class AdminTimeLogController extends Controller
{
    public function index(User $user)
    {
        $timeLogs = $user->timeLogs()->latest()->paginate(25);
        return view('admin.time-logs.index', compact('user', 'timeLogs'));
    }

    public function edit(User $user, TimeLog $timeLog)
    {
        $countries = Country::orderBy('name')->get();
        return view('admin.time-logs.edit', compact('user', 'timeLog', 'countries'));
    }

    public function update(UpdateTimeLogRequest $request, User $user, TimeLog $timeLog)
    {
        $timeLog->update($request->validated());

        return redirect()->route('admin.users.time-logs.index', ['user' => $user->id])->with('success', 'Time log updated successfully.');
    }

    public function destroy(User $user, TimeLog $timeLog)
    {
        $timeLog->delete();

        return redirect()->back()->with('success', 'Time log deleted successfully.');
    }
}
