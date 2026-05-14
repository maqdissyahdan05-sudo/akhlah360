@extends('layouts.app')
@section('title', 'Assessment Questions (AKHLAK)')
@section('subtitle', 'Manage core values and their corresponding assessment indicators')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <p class="text-sm text-gray-500">Total {{ $values->count() }} Core Values identified</p>
    </div>
    <a href="{{ route('admin.akhlaq-values.create') }}" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold flex items-center space-x-2 shadow-lg shadow-blue-200 transition-all transform hover:-translate-y-0.5">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        <span>Add New Core Value</span>
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    @foreach($values as $val)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-shadow">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100 flex justify-between items-start">
            <div>
                <h3 class="font-extrabold text-xl text-blue-900 brand-font uppercase tracking-tight">{{ $val->value_name }}</h3>
                <p class="text-blue-700/70 text-sm mt-1 line-clamp-2 italic">"{{ $val->description }}"</p>
            </div>
            <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <a href="{{ route('admin.akhlaq-values.edit', $val->value_id) }}" class="p-2 bg-white text-indigo-600 rounded-lg shadow-sm border border-indigo-100 hover:bg-indigo-600 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </a>
                <form action="{{ route('admin.akhlaq-values.destroy', $val->value_id) }}" method="POST" onsubmit="return confirm('Delete this core value and all its indicators?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 bg-white text-red-600 rounded-lg shadow-sm border border-red-100 hover:bg-red-600 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="p-6">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Assessment Indicators</p>
            <div class="space-y-3">
                @php $val->load('indicators'); @endphp
                @forelse($val->indicators as $index => $indicator)
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-xl border border-gray-100 group/item">
                        <span class="flex-shrink-0 w-6 h-6 bg-white text-blue-600 text-[10px] font-bold rounded-full flex items-center justify-center border border-blue-100 shadow-sm">
                            {{ $index + 1 }}
                        </span>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $indicator->indicator_statement }}</p>
                    </div>
                @empty
                    <div class="py-4 text-center text-gray-400 text-sm italic">
                        No indicators added for this value yet.
                    </div>
                @endforelse
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
            <span class="text-xs font-medium text-gray-500">{{ $val->indicators->count() }} Questions Total</span>
            <a href="{{ route('admin.akhlaq-values.edit', $val->value_id) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center">
                Edit Questions
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>
    @endforeach
</div>
@endsection
