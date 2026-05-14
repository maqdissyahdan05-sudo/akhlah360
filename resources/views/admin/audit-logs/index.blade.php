@extends('layouts.app')
@section('title', 'Audit Trail (Logs)')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Table / Module</label>
            <select name="table_name" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">All Tables</option>
                @foreach($tables as $tbl)
                    <option value="{{ $tbl }}" {{ request('table_name') == $tbl ? 'selected' : '' }}>{{ $tbl }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">Filter</button>
        <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 border text-gray-600 rounded-lg text-sm font-medium">Reset</a>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Timestamp</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">User</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Activity</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Table / Record ID</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $log->timestamp->format('Y-m-d H:i:s') }}</td>
                    <td class="px-4 py-3 font-medium">{{ $log->user->username ?? 'System' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-gray-100 text-gray-800">{{ $log->activity }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $log->table_name ?? '-' }} <span class="font-bold text-gray-700">#{{ $log->record_id ?? '-' }}</span></td>
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $log->ip_address }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">
        {{ $logs->links() }}
    </div>
</div>
@endsection
