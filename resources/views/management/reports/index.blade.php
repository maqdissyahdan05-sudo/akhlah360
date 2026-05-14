@extends('layouts.app')
@section('title', 'Performance Reports')
@section('content')
<div class="mb-6 flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-100">
    <form method="GET" class="flex space-x-4 w-full items-end">
        <div class="flex-1">
            <label class="block text-xs font-medium text-gray-500 mb-1">Period</label>
            <select name="period_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                @foreach($periods as $p)
                    <option value="{{ $p->period_id }}" {{ $selectedPeriodId == $p->period_id ? 'selected' : '' }}>{{ $p->period_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-xs font-medium text-gray-500 mb-1">Department</label>
            <select name="department_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">All Departments</option>
                @foreach($departments as $d)
                    <option value="{{ $d->department_id }}" {{ $selectedDepartmentId == $d->department_id ? 'selected' : '' }}>{{ $d->department_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">Apply Filters</button>
            <a href="{{ route('management.reports.export', ['period_id' => $selectedPeriodId, 'department_id' => $selectedDepartmentId]) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">Export CSV</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Self</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Peer</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Superior</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Subordinate</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-800 uppercase font-bold">Final Score</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($results as $res)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-bold text-gray-900">{{ $res->employee->employee_name }}</div>
                        <div class="text-xs text-gray-500">{{ $res->employee->department->department_name ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-3 text-center">{{ number_format($res->self_score, 2) }}</td>
                    <td class="px-4 py-3 text-center">{{ number_format($res->peer_score, 2) }}</td>
                    <td class="px-4 py-3 text-center">{{ number_format($res->superior_score, 2) }}</td>
                    <td class="px-4 py-3 text-center">{{ number_format($res->subordinate_score, 2) }}</td>
                    <td class="px-4 py-3 text-center font-bold text-blue-700 bg-blue-50">{{ number_format($res->final_score, 2) }}</td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('management.reports.show', $res->result_id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">No report data found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if(count($results))
            <div class="mt-4">{{ $results->links() }}</div>
        @endif
    </div>
</div>
@endsection
