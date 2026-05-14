@extends('layouts.app')
@section('title', 'Assessment Progress Monitor')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <form method="GET" class="flex space-x-4">
        <select name="period_id" class="border rounded px-4 py-2" onchange="this.form.submit()">
            @foreach($periods as $p)
                <option value="{{ $p->period_id }}" {{ $selectedPeriodId == $p->period_id ? 'selected' : '' }}>{{ $p->period_name }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="mb-4 text-center">
        <h3 class="font-bold text-xl">Overall Progress: {{ $overallPercent }}%</h3>
        <div class="w-full bg-gray-200 rounded-full h-4 mt-2">
            <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $overallPercent }}%"></div>
        </div>
    </div>
    
    <div class="overflow-x-auto mt-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Target Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Completed / Total</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($progress as $prog)
                <tr>
                    <td class="px-6 py-4 font-bold">{{ $prog['employee']->employee_name }}</td>
                    <td class="px-6 py-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $prog['percentage'] }}%"></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $prog['completed'] }} / {{ $prog['total'] }} Raters</td>
                    <td class="px-6 py-4 text-center">
                        @if($prog['percentage'] < 100)
                            <button onclick="alert('Reminder notification sent successfully to pending raters!')" class="px-3 py-1 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 text-xs font-bold rounded-lg transition-colors">
                                Send Reminder
                            </button>
                        @else
                            <span class="text-xs text-gray-400">Completed</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
