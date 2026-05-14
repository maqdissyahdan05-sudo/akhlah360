@extends('layouts.app')

@section('title', 'Employee Dashboard')
@section('subtitle', 'Welcome back, ' . $employee->employee_name)

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Status Overview -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
        <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Pending Tasks</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</h3>
        </div>
    </div>

    <!-- Latest Score -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
        <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Latest Score</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $latestResult ? number_format($latestResult->final_score, 2) : '-' }}</h3>
            @if($latestResult)
                <p class="text-xs text-gray-400">{{ $latestResult->period->period_name }}</p>
            @endif
        </div>
    </div>

    <!-- Quick Action -->
    <div class="relative rounded-2xl shadow-lg overflow-hidden group min-h-[160px]">
        <img src="{{ asset('images/building.png') }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="Building">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 to-indigo-900/60 flex flex-col justify-between p-6 text-white">
            <p class="text-sm font-medium opacity-80">Ready to Evaluate?</p>
            <div class="flex justify-between items-end mt-2">
                <h3 class="text-lg font-bold">Continue Evaluation</h3>
                <a href="{{ route('assessment.tasks') }}" class="px-4 py-2 bg-white text-blue-600 rounded-lg text-sm font-bold hover:bg-blue-50 transition-colors shadow-lg">
                    Open Tasks
                </a>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Activities -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Recent Activity</h3>
            <a href="{{ route('assessment.tasks') }}" class="text-xs font-semibold text-blue-600 hover:underline">View All</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentTasks as $task)
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                        @if($task->relationship_type == 'self')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $task->relationship_type == 'self' ? 'Self Assessment' : 'Evaluating ' . $task->ratee->employee_name }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $task->period->period_name }}</p>
                    </div>
                </div>
                <div>
                    @if($task->is_completed)
                        <span class="px-2 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-full uppercase">Completed</span>
                    @else
                        <span class="px-2 py-1 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-full uppercase">Pending</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center text-gray-400">
                <p>No assessment activity found.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Core Values Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-50 pb-4">Core Values AKHLAK</h3>
        <div class="space-y-4">
            <div class="p-4 bg-gray-50 rounded-xl">
                <h4 class="text-sm font-bold text-blue-700 mb-1">Amanah & Kompeten</h4>
                <p class="text-xs text-gray-500">Upholding trust and continuously learning to develop capabilities.</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl">
                <h4 class="text-sm font-bold text-blue-700 mb-1">Harmonis & Loyal</h4>
                <p class="text-xs text-gray-500">Caring and respecting differences, and prioritizing national interests.</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl">
                <h4 class="text-sm font-bold text-blue-700 mb-1">Adaptif & Kolaboratif</h4>
                <p class="text-xs text-gray-500">Innovating and embracing change, while building synergistic cooperation.</p>
            </div>
        </div>
    </div>
</div>
@endsection
