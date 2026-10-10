<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = User::where('role', Role::Employee)
            ->with('timeLogs')
            ->when($request->filled('username'), fn ($query) => $query->where('username', 'like', '%'.$request->username.'%'))
            ->get();

        return view('employees.index', compact('employees'));
    }
}
