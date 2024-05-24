@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Welcome, {{ Auth::user()->name }}</h1>
    </div>

    @if (Auth::user()->isEmployee())
        <div class="flex justify-center mb-6">
            <livewire:start-stop-timer />
        </div>
    @endif

    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">Your Time Logs</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border-b text-center">Login Time</th>
                    <th class="py-2 px-4 border-b text-center">Talk Time</th>
                    <th class="py-2 px-4 border-b text-center">Data</th>
                    <th class="py-2 px-4 border-b text-center">% Made</th>
                    <th class="py-2 px-4 border-b text-center">Start Time</th>
                    <th class="py-2 px-4 border-b text-center">End Time</th>
                    @if (Auth::user()->isAdmin())
                        <th class="py-2 px-4 border-b text-center">User</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($timeLogs as $timeLog)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b text-center">{{ $timeLog->duration }}</td>
                        <td class="py-2 px-4 border-b">{{ $timeLog->talk_time }}</td>
                        <td class="py-2 px-4 border-b">{{ $timeLog->data }}</td>
                        <td class="py-2 px-4 border-b">{{ $timeLog->made_percentage }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $timeLog->start_time->format('Y-m-d H:i:s') }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $timeLog->end_time ? $timeLog->end_time->format('Y-m-d H:i:s') : 'In Progress' }}</td>
                        @if (Auth::user()->isAdmin())
                            <td class="py-2 px-4 border-b">{{ $timeLog->user->name }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-center mt-6">
        <p class="text-gray-700">Total Tracked Time: {{ Auth::user()->total_tracked_time }}</p>
    </div>
</div>
@endsection