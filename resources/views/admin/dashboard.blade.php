@extends('layouts.app')
@section('title', 'Admin HR Dashboard')
@section('subtitle', 'System Overview of AKHLAK 360° Assessment')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
        <div><p class="text-sm font-medium text-gray-500">Total Employees</p><h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_employees'] }}</h3></div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
        <div class="p-3 bg-purple-50 text-purple-600 rounded-xl"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></div>
        <div><p class="text-sm font-medium text-gray-500">Departments</p><h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_departments'] }}</h3></div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></div>
        <div><p class="text-sm font-medium text-gray-500">User Accounts</p><h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</h3></div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
        <div class="p-3 bg-green-50 text-green-600 rounded-xl"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg></div>
        <div><p class="text-sm font-medium text-gray-500">Active Periods</p><h3 class="text-2xl font-bold text-gray-900">{{ $stats['active_periods'] }}</h3></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Active Progress -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Active Assessment Progress</h3>
        @if($progressData)
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-600 mb-1">{{ $progressData['period']->period_name }}</p>
                <div class="w-full bg-gray-200 rounded-full h-4 mb-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-4 rounded-full transition-all duration-1000" style="width: {{ $progressData['percent'] }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>{{ $progressData['completed'] }} completed</span>
                    <span>{{ $progressData['total'] }} total assignments ({{ $progressData['percent'] }}%)</span>
                </div>
            </div>
            <a href="{{ route('admin.progress.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View Details &rarr;</a>
        @else
            <div class="text-center py-8 text-gray-500">
                No active assessment periods.
            </div>
        @endif
    </div>

    <!-- Recent Periods -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Assessment Periods</h3>
        <div class="space-y-3">
            @forelse($recentPeriods as $period)
                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-50 hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="font-medium text-gray-800">{{ $period->period_name }}</p>
                        <p class="text-xs text-gray-500">{{ $period->start_date->format('M d, Y') }} - {{ $period->end_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                            @if($period->status === 'active') bg-green-100 text-green-800 
                            @elseif($period->status === 'closed') bg-gray-100 text-gray-800 
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($period->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-gray-500">No period data available.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
