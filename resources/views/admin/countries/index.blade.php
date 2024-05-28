@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800">Manage Countries</h1>
        </div>

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-right">
            <a href="{{ route('admin.countries.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Country
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 shadow-lg rounded-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-5 border-b text-center">#</th>
                        <th class="py-3 px-5 border-b text-center">Name</th>
                        <th class="py-3 px-5 border-b text-center">Code</th>
                        <th class="py-3 px-5 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($countries as $key => $country)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-5 border-b text-center">{{ $key + 1 }}</td>
                            <td class="py-3 px-5 border-b text-center">{{ $country->name }}</td>
                            <td class="py-3 px-5 border-b text-center">{{ $country->code }}</td>
                            <td class="py-3 px-5 border-b text-center">
                                <a href="{{ route('admin.countries.edit', $country->id) }}"
                                    class="text-blue-500 hover:underline">Edit</a>
                                <form action="{{ route('admin.countries.destroy', $country->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline ml-2">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection