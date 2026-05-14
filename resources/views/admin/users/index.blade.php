@extends('layouts.app')
@section('title', 'User Accounts Management')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">System Role</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 font-bold">{{ $user->username }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">{{ $user->role->role_name }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</div>
@endsection
