@extends('layouts.app')

@section('title', 'Rekapan & Export Assessment')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex-1 w-full">
        <form method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">Pilih Periode Penilaian</label>
                <select name="period_id" onchange="this.form.submit()" class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @foreach($periods as $p)
                        <option value="{{ $p->period_id }}" {{ $selectedPeriodId == $p->period_id ? 'selected' : '' }}>{{ $p->period_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <a href="{{ route('management.reports.export', ['period_id' => $selectedPeriodId]) }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 text-sm font-bold shadow-lg shadow-emerald-500/20 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download Rekapan CSV
                </a>
            </div>
        </form>
    </div>
</div>

@if($selectedPeriodId)
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform"></div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Karyawan</p>
            <h3 class="text-3xl font-extrabold text-gray-800">{{ $summary['total_employees'] }}</h3>
            <p class="text-xs text-blue-600 mt-2 font-medium">Seluruh Departemen</p>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform"></div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Assessment Selesai</p>
            <h3 class="text-3xl font-extrabold text-gray-800">{{ $summary['completed_assessments'] }}</h3>
            <p class="text-xs text-emerald-600 mt-2 font-medium">{{ round(($summary['completed_assessments'] / max(1, $summary['total_employees'])) * 100, 1) }}% Partisipasi</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-purple-50 rounded-full group-hover:scale-110 transition-transform"></div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Rata-rata Skor</p>
            <h3 class="text-3xl font-extrabold text-gray-800">{{ number_format($summary['avg_score'], 2) }}</h3>
            <p class="text-xs text-purple-600 mt-2 font-medium">Skor Akhir Nasional</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center">
        <h4 class="font-bold text-gray-800">Rekapan per Departemen</h4>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Departemen</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Karyawan Ter-assessment</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($departments as $dept)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-700">{{ $dept->department_name }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold">
                            {{ $dept->employees_count }} Karyawan
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('management.reports.export', ['period_id' => $selectedPeriodId, 'department_id' => $dept->department_id]) }}" class="text-emerald-600 hover:text-emerald-700 text-xs font-bold uppercase tracking-tight flex items-center justify-end">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Export CSV
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="bg-white p-12 rounded-3xl shadow-sm border border-gray-100 text-center">
    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
    </div>
    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada Periode Penilaian</h3>
    <p class="text-gray-500 max-w-xs mx-auto">Silakan buat periode penilaian terlebih dahulu untuk melihat rekapan laporan.</p>
</div>
@endif
@endsection
