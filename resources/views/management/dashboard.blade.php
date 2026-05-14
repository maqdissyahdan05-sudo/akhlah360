@extends('layouts.app')
@section('title', 'Management Dashboard')
@section('subtitle', 'AKHLAK 360° Assessment Executive Report')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
        <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
        </div>
        <p class="text-blue-100 text-sm font-medium">Active Period</p>
        <h3 class="text-2xl font-bold mt-1">{{ $stats['active_period'] }}</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
        <p class="text-sm font-medium text-gray-500">Overall Average Score</p>
        <div class="flex items-end mt-2 space-x-2">
            <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['avg_final_score'], 2) }}</h3>
            <span class="text-sm text-gray-400 pb-1">/ 5.00</span>
        </div>
        <p class="text-xs text-gray-400 mt-1">Based on last closed period</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
        <p class="text-sm font-medium text-gray-500">Total Rated Employees</p>
        <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_employees'] }}</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
        <p class="text-sm font-medium text-gray-500">Total Departments</p>
        <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_departments'] }}</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Top Performers -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-1">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Top 5 Performers</h3>
        @if(count($topPerformers) > 0)
            <div class="space-y-4">
                @foreach($topPerformers as $index => $result)
                    <div class="flex items-center justify-between p-3 rounded-xl border border-gray-50 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                #{{ $index + 1 }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $result->employee->employee_name }}</p>
                                <p class="text-xs text-gray-500">{{ $result->employee->department->department_name ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="font-bold text-green-600 bg-green-50 px-2 py-1 rounded-lg text-sm">
                            {{ number_format($result->final_score, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">No report data available yet.</div>
        @endif
    </div>

    <!-- Trend & Dept Chart (Placeholder) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Average Score by Department</h3>
            <a href="{{ route('management.reports.index') }}" class="text-sm text-blue-600 font-medium hover:underline">View Full Reports &rarr;</a>
        </div>
        
        @if(count($deptScores) > 0)
            <div class="space-y-4 mt-6">
                @foreach($deptScores as $dept => $score)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">{{ $dept }}</span>
                            <span class="font-bold text-gray-900">{{ number_format($score, 2) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-blue-400 to-indigo-500 h-2.5 rounded-full" style="width: {{ ($score / 5) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex items-center justify-center h-48 text-gray-400 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                Data not yet available for visualization
            </div>
        @endif
    </div>
</div>
@endsection
