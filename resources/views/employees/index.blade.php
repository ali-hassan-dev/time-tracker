@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Employee Time Logs</h1>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 shadow-lg rounded-lg">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-5 border-b text-center">Employee Name</th>
                    <th class="py-3 px-5 border-b text-center">Total Logged Time</th>
                    <th class="py-3 px-5 border-b text-center">View Logs</th> <!-- Added this header for the button -->
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-5 border-b text-center">{{ $employee->name }}</td>
                    <td class="py-3 px-5 border-b text-center">{{ $employee->total_tracked_time }}</td>
                    <td class="py-3 px-5 border-b text-center">
                        <a href="{{ route('admin.users.time-logs.index', ['user' => $employee->id]) }}" class="text-blue-500 hover:underline">View Logs</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection