@extends('layouts.app')
@section('title', 'Core Values AKHLAK')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-xl font-bold text-gray-800">Daftar Indikator Penilaian</h2>
    <a href="{{ route('admin.akhlaq-values.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center space-x-2">
        <span>+ Tambah Core Value</span>
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="space-y-6">
        @foreach($values as $val)
        <div class="p-4 border border-blue-100 rounded-lg bg-blue-50 flex justify-between items-start">
            <div>
                <h3 class="font-bold text-lg text-blue-900">{{ $val->value_name }}</h3>
                <p class="text-blue-700 text-sm mt-1">{{ $val->description }}</p>
                <div class="mt-3 text-xs font-semibold text-blue-600 bg-white inline-block px-3 py-1 rounded-full border border-blue-200">
                    {{ $val->indicators_count }} Indikator Penilaian
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.akhlaq-values.edit', $val->value_id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1 rounded-md border border-indigo-100">Edit Soal</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
