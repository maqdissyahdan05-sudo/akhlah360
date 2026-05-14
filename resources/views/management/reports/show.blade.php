@extends('layouts.app')
@section('title', 'Assessment Report Details')
@section('subtitle', $result->employee->employee_name . ' - ' . $result->period->period_name)

@section('content')
<div class="mb-6">
    <a href="{{ route('management.reports.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to Reports</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-blue-50 rounded-xl p-6 border border-blue-100 text-center">
        <p class="text-blue-600 text-xs font-bold uppercase tracking-wider">Self Score</p>
        <p class="text-3xl font-black text-blue-900 mt-2">{{ number_format($result->self_score, 2) }}</p>
    </div>
    <div class="bg-indigo-50 rounded-xl p-6 border border-indigo-100 text-center">
        <p class="text-indigo-600 text-xs font-bold uppercase tracking-wider">Peer Score</p>
        <p class="text-3xl font-black text-indigo-900 mt-2">{{ number_format($result->peer_score, 2) }}</p>
    </div>
    <div class="bg-purple-50 rounded-xl p-6 border border-purple-100 text-center">
        <p class="text-purple-600 text-xs font-bold uppercase tracking-wider">Superior Score</p>
        <p class="text-3xl font-black text-purple-900 mt-2">{{ number_format($result->superior_score, 2) }}</p>
    </div>
    <div class="bg-red-50 rounded-xl p-6 border border-red-100 text-center">
        <p class="text-red-600 text-xs font-bold uppercase tracking-wider">Gap Analysis</p>
        <p class="text-3xl font-black text-red-900 mt-2">
            @if($result->self_score && $result->final_score)
                {{ number_format($result->self_score - $result->final_score, 2) }}
            @else
                -
            @endif
        </p>
    </div>
    <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl p-6 shadow-lg text-center text-white">
        <p class="text-blue-100 text-xs font-bold uppercase tracking-wider">Final 360° Score</p>
        <p class="text-4xl font-black mt-2">{{ number_format($result->final_score, 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Evaluator Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold mb-4 text-gray-800">Evaluator Details</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evaluator</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Relationship</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($assignments as $assign)
                    <tr>
                        <td class="px-6 py-4 font-medium">{{ $assign->rater->employee_name }}</td>
                        <td class="px-6 py-4 capitalize">{{ $assign->relationship_type }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Employee Performance Trend -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold mb-4 text-gray-800">Employee Performance Trend</h3>
        <div class="space-y-4">
            @foreach($trend as $t)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-gray-700">{{ $t->period->period_name }}</span>
                    <span class="font-bold text-gray-900">{{ number_format($t->final_score, 2) }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="bg-gradient-to-r from-blue-400 to-indigo-500 h-2.5 rounded-full" style="width: {{ ($t->final_score / 5) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
