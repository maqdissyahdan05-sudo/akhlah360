@extends('layouts.app')
@section('title', 'Buat Form / Core Value Baru')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl">
    <form action="{{ route('admin.akhlaq-values.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-medium mb-1">Nama Core Value (Contoh: Amanah)</label>
            <input type="text" name="value_name" class="w-full border rounded-lg px-4 py-2" required>
        </div>
        
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi Core Value</label>
            <textarea name="description" class="w-full border rounded-lg px-4 py-2" rows="3"></textarea>
        </div>

        <div class="border-t pt-4">
            <h3 class="font-bold text-lg mb-2">Soal / Indikator Penilaian</h3>
            <p class="text-sm text-gray-500 mb-4">Masukkan pernyataan soal yang akan dinilai dengan skala 1-5.</p>
            
            <div id="indicators-container" class="space-y-3">
                <div class="flex items-center space-x-2 indicator-row">
                    <input type="text" name="indicators[0][statement]" class="flex-1 border rounded-lg px-4 py-2" placeholder="Contoh: Memenuhi janji dan komitmen" required>
                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 text-red-600 bg-red-50 rounded-lg font-bold">&times;</button>
                </div>
            </div>
            
            <button type="button" onclick="addIndicator()" class="mt-4 px-4 py-2 bg-green-50 text-green-700 font-medium rounded-lg hover:bg-green-100 text-sm">
                + Tambah Soal Indikator
            </button>
        </div>

        <div class="pt-6 border-t">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan Core Value & Soal</button>
            <a href="{{ route('admin.akhlaq-values.index') }}" class="ml-3 text-gray-600">Batal</a>
        </div>
    </form>
</div>

<script>
    let indicatorCount = 1;
    function addIndicator() {
        const container = document.getElementById('indicators-container');
        const row = document.createElement('div');
        row.className = 'flex items-center space-x-2 indicator-row';
        row.innerHTML = `
            <input type="text" name="indicators[${indicatorCount}][statement]" class="flex-1 border rounded-lg px-4 py-2" placeholder="Masukkan pernyataan soal..." required>
            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 text-red-600 bg-red-50 rounded-lg font-bold">&times;</button>
        `;
        container.appendChild(row);
        indicatorCount++;
    }
</script>
@endsection
