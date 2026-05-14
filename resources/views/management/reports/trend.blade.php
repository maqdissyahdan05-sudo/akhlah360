@extends('layouts.app')
@section('title', 'Performance Trend Analysis')
@section('subtitle', 'Monitoring organizational performance growth over time')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-100">
        <form action="{{ route('management.reports.trend') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Department</label>
                <select name="department_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all" onchange="this.form.submit()">
                    <option value="">All Departments (Company Wide)</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->department_id }}" {{ $selectedDeptId == $dept->department_id ? 'selected' : '' }}>
                            {{ $dept->department_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-shrink-0">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">Apply Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Company Performance Scorecard</h3>
            <p class="text-gray-500 text-sm">Average scores across assessment periods</p>
        </div>
        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
        </div>
    </div>
    
    <div class="h-96">
        <canvas id="trendChart"></canvas>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($trendData as $data)
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-500 font-medium mb-1">{{ $data['period'] }}</p>
            <div class="flex items-end justify-between">
                <h4 class="text-3xl font-bold text-blue-600">{{ $data['average'] }}</h4>
                <span class="text-xs font-bold px-2 py-1 bg-green-100 text-green-700 rounded">
                    Score / 5.0
                </span>
            </div>
        </div>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('trendChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($trendData)->pluck('period')) !!},
            datasets: [{
                label: 'Average Performance Score',
                data: {!! json_encode(collect($trendData)->pluck('average')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgb(59, 130, 246)',
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true, 
                    max: 5,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection
