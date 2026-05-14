@extends('layouts.app')
@section('title', 'Employee Directory')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($employees as $emp)
                <tr>
                    <td class="px-6 py-4">{{ $emp->employee_number }}</td>
                    <td class="px-6 py-4 font-bold">{{ $emp->employee_name }}</td>
                    <td class="px-6 py-4">{{ $emp->department->department_name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $employees->links() }}</div>
    </div>
</div>
@endsection
