@extends('layouts.app')
@section('title', '360° Assessment Form')
@section('subtitle', 'Evaluating: ' . $assignment->ratee->employee_name)

@section('content')
<div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row md:items-center justify-between p-6">
    <div class="flex items-center space-x-4 mb-4 md:mb-0">
        <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-indigo-500 to-blue-500 text-white flex items-center justify-center font-bold text-xl shadow-inner">
            {{ substr($assignment->ratee->employee_name, 0, 1) }}
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $assignment->ratee->employee_name }}</h2>
            <p class="text-sm text-gray-500">{{ $assignment->ratee->department->department_name ?? '-' }} &bull; {{ $assignment->ratee->employee_number }}</p>
        </div>
    </div>
    <div class="flex items-center space-x-3 text-right">
        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Relationship Type</p>
            <p class="text-sm font-bold text-indigo-700 capitalize">{{ $assignment->relationship_type }}</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Period</p>
            <p class="text-sm font-bold text-gray-800">{{ $assignment->period->period_name }}</p>
        </div>
    </div>
</div>

<form action="{{ route('assessment.form.store', $assignment->assignment_id) }}" method="POST">
    @csrf
    
    <div class="space-y-8">
        @foreach($akhlaqValues as $value)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $value->value_name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $value->description }}</p>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @foreach($value->indicators as $index => $indicator)
                        <div class="p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-6 hover:bg-blue-50/30 transition-colors">
                            <div class="flex-1">
                                <p class="text-gray-800 font-medium leading-relaxed">{{ $index + 1 }}. {{ $indicator->indicator_statement }}</p>
                            </div>
                            <div class="flex-shrink-0 flex items-center justify-between lg:justify-end space-x-2">
                                <span class="text-xs text-gray-400 font-medium mr-2 hidden md:inline">Poor</span>
                                <div class="flex space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer relative">
                                            <input type="radio" name="scores[{{ $indicator->indicator_id }}]" value="{{ $i }}" class="peer sr-only" required>
                                            <div class="w-10 h-10 rounded-xl border-2 border-gray-200 flex items-center justify-center text-sm font-bold text-gray-400 hover:border-blue-400 peer-checked:border-blue-600 peer-checked:bg-blue-600 peer-checked:text-white transition-all transform peer-checked:scale-110 shadow-sm">
                                                {{ $i }}
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-400 font-medium ml-2 hidden md:inline">Excellent</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 flex justify-end space-x-4">
        <a href="{{ route('assessment.tasks') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
            Cancel
        </a>
        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform transition hover:-translate-y-0.5">
            Submit Assessment
        </button>
    </div>
</form>
@endsection
