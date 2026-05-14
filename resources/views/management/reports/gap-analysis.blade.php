@extends('layouts.app')
@section('title', 'Gap Analysis Report')
@section('subtitle', 'Identifying the difference between current and target performance')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-100">
        <form action="{{ route('management.reports.gap-analysis') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Period</label>
                <select name="period_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all" onchange="this.form.submit()">
                    @foreach($periods as $p)
                        <option value="{{ $p->period_id }}" {{ $selectedPeriodId == $p->period_id ? 'selected' : '' }}>
                            {{ $p->period_name }} ({{ $p->status }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-shrink-0">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Chart Section -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6">Gap Analysis Overview</h3>
        <div class="h-80">
            <canvas id="gapChart"></canvas>
        </div>
    </div>

    <!-- Top Gaps List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Bottom 10 Performers</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($results as $res)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="font-bold text-gray-900 text-sm">{{ $res->employee->employee_name }}</p>
                        <p class="text-xs text-gray-500">{{ $res->employee->department->department_name ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-red-600">{{ $res->final_score }}</p>
                        <p class="text-[10px] text-gray-400">Score</p>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">No data found.</div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('gapChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($results->pluck('employee.employee_name')) !!},
            datasets: [{
                label: 'Current Score',
                data: {!! json_encode($results->pluck('final_score')) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }, {
                label: 'Target (Ideal)',
                data: Array({!! count($results) !!}).fill(5),
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderColor: 'rgba(16, 185, 129, 0.4)',
                borderWidth: 1,
                borderDash: [5, 5],
                type: 'line'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 5 }
            }
        }
    });
</script>
@endsection
