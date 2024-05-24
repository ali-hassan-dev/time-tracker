@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Welcome, {{ Auth::user()->name }}</h1>
    </div>

    @unless (Auth::user()->isAdmin())
    <div class="flex justify-center mb-8">
        <livewire:start-stop-timer />
    </div>
    @endunless

    <div class="text-center mb-8">
        @if (Auth::user()->isAdmin())
        <h2 class="text-3xl font-semibold text-gray-700">Time Logs</h2>
        @else
        <h2 class="text-3xl font-semibold text-gray-700">Your Time Logs</h2>
        <p class="text-xl text-gray-700">Total Tracked Time: <span class="font-bold">{{ Auth::user()->total_tracked_time }}</span></p>
        @endif
    </div>

    <div class="flex justify-center mb-6">
        <a href="{{ route('employees.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out">
            View All Employees
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 shadow-lg rounded-lg">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-5 border-b text-center">Login Time</th>
                    <th class="py-3 px-5 border-b text-center">Talk Time</th>
                    <th class="py-3 px-5 border-b text-center">Date</th>
                    <th class="py-3 px-5 border-b text-center">% Made</th>
                    <th class="py-3 px-5 border-b text-center">Start Time</th>
                    <th class="py-3 px-5 border-b text-center">End Time</th>
                    @if (Auth::user()->isAdmin())
                    <th class="py-3 px-5 border-b text-center">User</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($timeLogs as $timeLog)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-5 border-b text-center">{{ $timeLog->duration }}</td>
                    <td class="py-3 px-5 border-b text-center">{{ $timeLog->talk_time }}</td>
                    <td class="py-3 px-5 border-b text-center">{{ $timeLog->date }}</td>
                    <td class="py-3 px-5 border-b text-center">{{ $timeLog->made_percentage }}</td>
                    <td class="py-3 px-5 border-b text-center">{{ $timeLog->start_time->format('Y-m-d H:i:s') }}</td>
                    <td class="py-3 px-5 border-b text-center">{{ $timeLog->end_time ? $timeLog->end_time->format('Y-m-d H:i:s') : 'In Progress' }}</td>
                    @if (Auth::user()->isAdmin())
                    <td class="py-3 px-5 border-b text-center">{{ $timeLog->user->name }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection