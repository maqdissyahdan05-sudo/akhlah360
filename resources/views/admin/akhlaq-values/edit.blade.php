@extends('layouts.app')
@section('title', 'Edit Soal / Core Value')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl">
    <form action="{{ route('admin.akhlaq-values.update', $akhlaqValue->value_id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-medium mb-1">Nama Core Value</label>
            <input type="text" name="value_name" value="{{ $akhlaqValue->value_name }}" class="w-full border rounded-lg px-4 py-2" required>
        </div>
        
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" class="w-full border rounded-lg px-4 py-2" rows="3">{{ $akhlaqValue->description }}</textarea>
        </div>

        <div class="border-t pt-4">
            <h3 class="font-bold text-lg mb-2">Soal / Indikator Penilaian</h3>
            <p class="text-sm text-gray-500 mb-4">Edit soal yang akan muncul di formulir penilaian 360.</p>
            
            <div id="indicators-container" class="space-y-3">
                @foreach($akhlaqValue->indicators as $index => $indicator)
                <div class="flex items-center space-x-2 indicator-row">
                    <input type="hidden" name="indicators[{{ $index }}][id]" value="{{ $indicator->indicator_id }}">
                    <input type="text" name="indicators[{{ $index }}][statement]" value="{{ $indicator->indicator_statement }}" class="flex-1 border rounded-lg px-4 py-2" required>
                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 text-red-600 bg-red-50 rounded-lg font-bold">&times;</button>
                </div>
                @endforeach
            </div>
            
            <button type="button" onclick="addIndicator()" class="mt-4 px-4 py-2 bg-green-50 text-green-700 font-medium rounded-lg hover:bg-green-100 text-sm">
                + Tambah Soal Indikator
            </button>
        </div>

        <div class="pt-6 border-t">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700">Perbarui Data</button>
            <a href="{{ route('admin.akhlaq-values.index') }}" class="ml-3 text-gray-600">Batal</a>
        </div>
    </form>
</div>

<script>
    let indicatorCount = {{ count($akhlaqValue->indicators) + 100 }};
    function addIndicator() {
        const container = document.getElementById('indicators-container');
        const row = document.createElement('div');
        row.className = 'flex items-center space-x-2 indicator-row';
        row.innerHTML = `
            <input type="hidden" name="indicators[${indicatorCount}][id]" value="">
            <input type="text" name="indicators[${indicatorCount}][statement]" class="flex-1 border rounded-lg px-4 py-2" placeholder="Masukkan pernyataan soal..." required>
            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 text-red-600 bg-red-50 rounded-lg font-bold">&times;</button>
        `;
        container.appendChild(row);
        indicatorCount++;
    }
</script>
@endsection
