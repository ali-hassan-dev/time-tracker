@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800">Employee Time Logs</h1>
        </div>

        <div class="mb-4">
            <form action="{{ route('employees.index') }}" method="GET" class="flex items-center justify-center">
                <input type="text" name="username" placeholder="Filter by username"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ request('username') }}" />
                <button type="submit"
                    class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Filter
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 shadow-lg rounded-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-5 border-b text-center">#</th>
                        <th class="py-3 px-5 border-b text-center">Employee Name</th>
                        <th class="py-3 px-5 border-b text-center">Username</th>
                        <th class="py-3 px-5 border-b text-center">Email</th>
                        <th class="py-3 px-5 border-b text-center">Total Logged Time</th>
                        @if (Auth::user()->isAdmin())
                            <th class="py-3 px-5 border-b text-center">View Logs</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $index => $employee)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-5 border-b text-center">{{ $index + 1 }}</td>
                            <td class="py-3 px-5 border-b text-center">{{ $employee->name }}</td>
                            <td class="py-3 px-5 border-b text-center">{{ $employee->username }}</td>
                            <td class="py-3 px-5 border-b text-center">{{ $employee->email }}</td>
                            <td class="py-3 px-5 border-b text-center">{{ $employee->total_tracked_time }}</td>
                            @if (Auth::user()->isAdmin())
                                <td class="py-3 px-5 border-b text-center">
                                    <a href="{{ route('admin.users.time-logs.index', ['user' => $employee->id]) }}"
                                        class="text-blue-500 hover:underline">View Logs</a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection