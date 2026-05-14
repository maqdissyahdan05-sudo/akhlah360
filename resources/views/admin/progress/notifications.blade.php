@extends('layouts.app')
@section('title', 'Send Notifications')
@section('subtitle', 'Remind employees to complete their 360° assessments')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.progress.notifications') }}" method="GET" class="flex flex-1 gap-4">
            <div class="flex-1 max-w-xs">
                <select name="period_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all text-sm" onchange="this.form.submit()">
                    @foreach($periods as $p)
                        <option value="{{ $p->period_id }}" {{ $selectedPeriodId == $p->period_id ? 'selected' : '' }}>
                            {{ $p->period_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        @if(count($pendingEmployees) > 0)
            <form action="{{ route('admin.progress.send-notification') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition-all flex items-center shadow-lg shadow-indigo-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    Remind All Pending
                </button>
            </form>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Employee Name</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Pending Forms</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Tasks for</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pendingEmployees as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm mr-3">
                                    {{ substr($item['employee']->employee_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $item['employee']->employee_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item['employee']->department->department_name ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold">
                                {{ $item['count'] }} Pending
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-gray-600 max-w-xs truncate" title="{{ $item['tasks'] }}">
                                {{ $item['tasks'] }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.progress.send-notification') }}" method="POST">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $item['employee']->employee_id }}">
                                <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-bold flex items-center justify-end w-full">
                                    Send Reminder
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-lg font-medium">All assessments completed!</p>
                                <p class="text-sm">No pending tasks for this period.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
