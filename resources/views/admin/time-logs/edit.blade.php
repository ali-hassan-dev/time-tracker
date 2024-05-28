@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800">Edit Time Log</h1>
        </div>

        <div class="max-w-lg mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <form action="{{ route('admin.users.time-logs.update', ['user' => $user->id, 'timeLog' => $timeLog->id]) }}"
                method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="login_time" class="block text-gray-700 text-sm font-bold mb-2">Login Time:</label>
                    <input type="datetime-local" name="login_time" id="login_time"
                        value="{{ old('login_time', optional($timeLog->login_time)->format('Y-m-d\TH:i:s') ?? '') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('login_time')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="logout_time" class="block text-gray-700 text-sm font-bold mb-2">Logout Time:</label>
                    <input type="datetime-local" name="logout_time" id="logout_time"
                        value="{{ old('logout_time', optional($timeLog->logout_time)->format('Y-m-d\TH:i') ?? '') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('logout_time')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="talk_type" class="block text-gray-700 text-sm font-bold mb-2">Talk Type:</label>
                    <select name="talk_type" id="talk_type"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="Chat" {{ old('talk_type', $timeLog->talk_type) == 'Chat' ? 'selected' : '' }}>Chat
                        </option>
                        <option value="Talk" {{ old('talk_type', $timeLog->talk_type) == 'Talk' ? 'selected' : '' }}>Talk
                        </option>
                    </select>
                    @error('talk_type')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="talk_time" class="block text-gray-700 text-sm font-bold mb-2">Talk Time (Minutes):</label>
                    <input type="number" name="talk_time" id="talk_time"
                        value="{{ old('talk_time', $timeLog->talk_time) ?? '' }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        min="0">
                    @error('talk_time')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="client" class="block text-gray-700 text-sm font-bold mb-2">Client:</label>
                    <input type="text" name="client" id="client" value="{{ old('client', $timeLog->client) ?? '' }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('client')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date:</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $timeLog->date) ?? '' }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="payout" class="block text-gray-700 text-sm font-bold mb-2">Payout:</label>
                    <input type="number" name="payout" id="payout" value="{{ old('payout', $timeLog->payout) ?? '' }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('payout')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="country" class="block text-gray-700 text-sm font-bold mb-2">Country:</label>
                    <select name="country" id="country"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach ($countries as $country)
                            <option value="{{ $country->name }}"
                                {{ old('country', $timeLog->country) == $country->name ? 'selected' : '' }}>
                                {{ $country->name }}</option>
                        @endforeach
                    </select>
                    @error('country')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="payment_method" class="block text-gray-700 text-sm font-bold mb-2">Payment Method:</label>
                    <select name="payment_method" id="payment_method"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="Phone"
                            {{ old('payment_method', $timeLog->payment_method) == 'Phone' ? 'selected' : '' }}>Phone
                            Pay</option>
                        <option value="Crypto"
                            {{ old('payment_method', $timeLog->payment_method) == 'Crypto' ? 'selected' : '' }}>Crypto
                        </option>
                    </select>
                    @error('payment_method')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-6">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update</button>
                    <a href="{{ route('admin.users.time-logs.index', ['user' => $user->id]) }}"
                        class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection