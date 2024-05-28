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
                <p class="text-xl text-gray-700">Total Tracked Time: <span
                        class="font-bold">{{ Auth::user()->total_tracked_time }}</span></p>
            @endif
        </div>

        <div class="flex justify-center mb-6">
            <a href="{{ route('employees.index') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out">
                View All Employees
            </a>
        </div>

        <div class="mb-8">
            <form action="{{ route('timelogs.index') }}" method="GET"
                class="flex flex-wrap justify-center space-x-4 space-y-4 md:space-y-0">
                <div class="w-full md:w-auto">
                    <label for="date_from" class="block text-gray-700 text-sm font-bold mb-2">From:</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                </div>
                <div class="w-full md:w-auto">
                    <label for="date_to" class="block text-gray-700 text-sm font-bold mb-2">To:</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                </div>
                <div class="w-full md:w-auto">
                    <label for="talk_type" class="block text-gray-700 text-sm font-bold mb-2">Talk Type:</label>
                    <select name="talk_type" id="talk_type"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">All</option>
                        <option value="Chat"{{ request('talk_type') == 'Chat' ? ' selected' : '' }}>Chat</option>
                        <option value="Talk"{{ request('talk_type') == 'Talk' ? ' selected' : '' }}>Talk</option>
                    </select>
                </div>
                <div class="w-full md:w-auto">
                    <label for="client" class="block text-gray-700 text-sm font-bold mb-2">Client:</label>
                    <input type="text" name="client" id="client" value="{{ request('client') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Client name" />
                </div>
                <div class="w-full md:w-auto">
                    <label for="country" class="block text-gray-700 text-sm font-bold mb-2">Country:</label>
                    <select name="country" id="country"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">All</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country }}"{{ request('country') == $country ? ' selected' : '' }}>
                                {{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-auto">
                    <label for="payment_method" class="block text-gray-700 text-sm font-bold mb-2">Payment Method:</label>
                    <select name="payment_method" id="payment_method"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">All</option>
                        <option value="Phone"{{ request('payment_method') == 'Phone' ? ' selected' : '' }}>Phone</option>
                        <option value="Crypto"{{ request('payment_method') == 'Crypto' ? ' selected' : '' }}>Crypto
                        </option>
                    </select>
                </div>
                <div class="w-full md:w-auto flex items-end">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Filter</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 shadow-lg rounded-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-5 border-b text-center">#</th>
                        <th class="py-3 px-5 border-b text-center">Duration</th>
                        <th class="py-3 px-5 border-b text-center">Talk Time</th>
                        <th class="py-3 px-5 border-b text-center">Date</th>
                        <th class="py-3 px-5 border-b text-center">Payout</th>
                        <th class="py-3 px-5 border-b text-center">Start Time</th>
                        <th class="py-3 px-5 border-b text-center">End Time</th>
                        @if (Auth::user()->isAdmin())
                            <th class="py-3 px-5 border-b text-center">User</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($timeLogs as $key => $timeLog)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-5 border-b text-center">{{ $key + 1 }}</td>
                            <td class="py-3 px-5 border-b text-center">{{ $timeLog->duration ?? '-' }}</td>
                            <td class="py-3 px-5 border-b text-center">
                                {{ $timeLog->talk_time ? $timeLog->talk_time . ' minutes' : '-' }}</td>
                            <td class="py-3 px-5 border-b text-center">{{ $timeLog->date ?? '-' }}</td>
                            <td class="py-3 px-5 border-b text-center">{{ $timeLog->payout ?? '-' }}</td>
                            <td class="py-3 px-5 border-b text-center">
                                {{ $timeLog->start_time->format('Y-m-d H:i:s') ?? '-' }}</td>
                            <td class="py-3 px-5 border-b text-center">
                                {{ $timeLog->end_time ? $timeLog->end_time->format('Y-m-d H:i:s') : 'In Progress' }}</td>
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