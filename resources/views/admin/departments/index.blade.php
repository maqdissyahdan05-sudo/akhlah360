@extends('layouts.app')
@section('title', 'Departments Management')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Employees</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($departments as $dept)
                <tr>
                    <td class="px-6 py-4 font-bold">{{ $dept->department_name }}</td>
                    <td class="px-6 py-4">{{ $dept->employees_count }} Employees</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $departments->links() }}</div>
    </div>
</div>
@endsection
