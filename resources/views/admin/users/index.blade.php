@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800">All Users</h1>
    </div>

    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add User</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 shadow-lg rounded-lg">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-4 border-b text-center">#</th>
                    <th class="py-3 px-4 border-b text-center">Name</th>
                    <th class="py-3 px-4 border-b text-center">Username</th>
                    <th class="py-3 px-4 border-b text-center">Email</th>
                    <th class="py-3 px-4 border-b text-center">Role</th>
                    <th class="py-3 px-4 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $key => $user)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 border-b text-center">{{ $key + 1 }}</td>
                    <td class="py-3 px-4 border-b text-center">{{ $user->name }}</td>
                    <td class="py-3 px-4 border-b text-center">{{ $user->username }}</td>
                    <td class="py-3 px-4 border-b text-center">{{ $user->email }}</td>
                    <td class="py-3 px-4 border-b text-center">{{ ucfirst($user->role->value) }}</td>
                    <td class="py-3 px-4 border-b text-center">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-500 hover:underline">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
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