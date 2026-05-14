@extends('layouts.app')
@section('title', '360° Assessment Tasks')
@section('subtitle', 'List of employees awaiting your evaluation')

@section('content')
<div class="mb-8">
    <div class="bg-blue-50 border border-blue-100 p-6 rounded-2xl flex items-start space-x-4">
        <div class="p-3 bg-blue-100 text-blue-600 rounded-full flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <h4 class="text-blue-900 font-bold text-lg">Evaluation Guide</h4>
            <p class="text-blue-800 mt-1 text-sm leading-relaxed">
                The 360° assessment measures the implementation of Core Values AKHLAK. Please provide an objective and honest evaluation. The rating scale is from 1 (Very Poor) to 5 (Excellent).
            </p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Pending Evaluations -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Pending Evaluations</h3>
            <span class="bg-red-100 text-red-700 py-1 px-3 rounded-full text-xs font-bold">{{ count($pendingAssignments) }} Tasks</span>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($pendingAssignments as $assignment)
                <div class="p-6 hover:bg-gray-50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            <p class="font-bold text-gray-900 text-lg">{{ $assignment->ratee->employee_name }}</p>
                            <span class="px-2 py-0.5 text-[10px] uppercase tracking-wider font-bold rounded {{ 
                                $assignment->relationship_type === 'superior' ? 'bg-purple-100 text-purple-700' : 
                                ($assignment->relationship_type === 'subordinate' ? 'bg-blue-100 text-blue-700' : 
                                ($assignment->relationship_type === 'peer' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700')) 
                            }}">
                                @if($assignment->relationship_type === 'superior') Assessing Subordinate
                                @elseif($assignment->relationship_type === 'subordinate') Assessing Superior
                                @elseif($assignment->relationship_type === 'peer') Assessing Peer
                                @else Self-Assessment @endif
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">{{ $assignment->period->period_name }}</p>
                    </div>
                    <a href="{{ route('assessment.form.show', $assignment->assignment_id) }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all transform hover:-translate-y-0.5">
                        Start Evaluation &rarr;
                    </a>
                </div>
            @empty
                <div class="p-8 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium">Great! You have completed all pending evaluation tasks.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Completion History -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Evaluation History</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($completedAssignments as $assignment)
                <div class="p-4 sm:p-6 flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-2">
                            <p class="font-medium text-gray-800">{{ $assignment->ratee->employee_name }}</p>
                            <span class="text-xs font-bold uppercase tracking-tighter {{ 
                                $assignment->relationship_type === 'superior' ? 'text-purple-500' : 
                                ($assignment->relationship_type === 'subordinate' ? 'text-blue-500' : 
                                ($assignment->relationship_type === 'peer' ? 'text-orange-500' : 'text-green-500')) 
                            }}">
                                @if($assignment->relationship_type === 'superior') Subordinate
                                @elseif($assignment->relationship_type === 'subordinate') Superior
                                @elseif($assignment->relationship_type === 'peer') Peer
                                @else Self @endif
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Completed on: {{ $assignment->completed_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="text-green-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    No evaluation history found.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
