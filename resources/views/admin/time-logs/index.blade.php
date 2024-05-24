@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Time Logs for {{ $user->name }}</h1>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 shadow-lg rounded-lg">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-4 border-b text-center">Login Time</th>
                    <th class="py-3 px-4 border-b text-center">Logout Time</th>
                    <th class="py-3 px-4 border-b text-center">Talk Type</th>
                    <th class="py-3 px-4 border-b text-center">Talk Time</th>
                    <th class="py-3 px-4 border-b text-center">Order Type</th>
                    <th class="py-3 px-4 border-b text-center">Country</th>
                    <th class="py-3 px-4 border-b text-center">Date</th>
                    <th class="py-3 px-4 border-b text-center">% Made</th>
                    <th class="py-3 px-4 border-b text-center">Payment Method</th>
                    <th class="py-3 px-4 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timeLogs as $timeLog)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 border-b text-center">{{ $timeLog->login_time ?? "-" }}</td>
                    <td class="py-3 px-4 border-b text-center">{{ $timeLog->logout_time ?? "-" }}</td>
                    <td class="py-3 px-4 border-b text-center">{{ $timeLog->talk_type ?? "-" }}</td>
                    <td class="py-3 px-4 border-b text-center">{{ $timeLog->talk_time ?? "-" }}</td>
                    <td class="py-3 px-4 border-b text-center">{{ $timeLog->order_type ?? "-" }}</td>
                    <td class="py-3 px-4 border-b text-center">{{ $timeLog->country ?? "-" }}</td>
                    <td class="py-3 px-4 border-b text-center">{{ $timeLog->date ?? "-" }}</td>
                    <td class="py-3 px-4 border-b text-center">{{ $timeLog->made_percentage ?? "-" }}</td>
                    <td class="py-3 px-4 border-b text-center">{{ $timeLog->payment_method ?? "-" }}</td>
                    <td class="py-3 px-4 border-b text-center">
                        <a href="{{ route('admin.users.time-logs.edit', ['user' => $user->id, 'timeLog' => $timeLog->id]) }}" class="text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection