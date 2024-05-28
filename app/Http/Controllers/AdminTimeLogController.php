<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Validation\Rule;
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
        $countries = Country::orderBy('name')->get();
        return view('admin.time-logs.edit', compact('user', 'timeLog', 'countries'));
    }

    public function update(Request $request, User $user, TimeLog $timeLog)
    {
        $validatedData = $request->validate([
            'login_time' => 'nullable|date',
            'logout_time' => 'nullable|date',
            'talk_type' => 'nullable|in:Chat,Talk',
            'talk_time' => 'nullable|integer',
            'client' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'payout' => 'nullable|numeric',
            'country' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists('countries', 'name')->where(function ($query) use ($request) {
                    $query->where('name', $request->input('country'));
                }),
            ],
            'payment_method' => 'nullable|in:Phone,Crypto',
        ]);

        $timeLog->update($validatedData);

        return redirect()->route('admin.users.time-logs.index', ['user' => $user->id])->with('success', 'Time log updated successfully.');
    }

    public function destroy(User $user, TimeLog $timeLog)
    {
        $timeLog->delete();

        return redirect()->back()->with('success', 'Time log deleted successfully.');
    }
}
