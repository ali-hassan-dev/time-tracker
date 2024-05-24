<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', Role::Employee)->get();

        return view('employees.index', compact('employees'));
    }
}
