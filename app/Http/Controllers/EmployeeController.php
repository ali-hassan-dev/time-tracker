<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', Role::Employee);
        if ($request->has('username') && !empty($request->username)) {
            $query->where('username', 'like', '%' . $request->username . '%');
        }
        $employees = $query->get();

        return view('employees.index', compact('employees'));
    }
}
