@extends('layouts.app')
@section('title', 'Add New Core Value & Questions')
@section('subtitle', 'Define a new AKHLAK value and its corresponding assessment indicators')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.akhlaq-values.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Basic Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </span>
                        New Value Info
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Core Value Name</label>
                            <input type="text" name="value_name" value="{{ old('value_name') }}" 
                                   class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all font-bold text-gray-900" 
                                   placeholder="e.g. LOYAL" required>
                            @error('value_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Description / Definition</label>
                            <textarea name="description" rows="4" 
                                      class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all text-sm text-gray-600" 
                                      placeholder="What does this value represent?">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-600 rounded-2xl shadow-lg p-6 text-white overflow-hidden relative group">
                    <svg class="w-24 h-24 absolute -right-4 -bottom-4 text-indigo-500/20 transform group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
                    <h4 class="font-bold relative z-10">Note</h4>
                    <p class="text-xs text-indigo-100 mt-2 leading-relaxed relative z-10">
                        Adding a Core Value will automatically make it available for all active assessment periods. Make sure to add at least one indicator.
                    </p>
                </div>
            </div>

            <!-- Right Side: Indicators Management -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" 
                     x-data="{ 
                        items: [{ id: '', statement: '' }],
                        addItem() {
                            this.items.push({ id: '', statement: '' });
                        },
                        removeItem(index) {
                            if(this.items.length > 1) {
                                this.items.splice(index, 1);
                            }
                        }
                     }">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-900">Initial Assessment Questions</h3>
                        <button type="button" @click="addItem()" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-600 hover:text-white transition-all flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Question
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex items-start space-x-3 group animate-fadeIn">
                                    <div class="flex-shrink-0 mt-2.5">
                                        <span class="w-6 h-6 bg-gray-100 text-gray-500 text-[10px] font-bold rounded-lg flex items-center justify-center border border-gray-200" x-text="index + 1"></span>
                                    </div>
                                    <div class="flex-1">
                                        <textarea :name="'indicators['+index+'][statement]'" 
                                                  x-model="item.statement"
                                                  class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all text-sm py-2" 
                                                  rows="2"
                                                  placeholder="e.g. Demonstrates strong commitment to the company goals." required></textarea>
                                    </div>
                                    <button type="button" @click="removeItem(index)" 
                                            class="mt-2 text-gray-300 hover:text-red-500 transition-colors p-1"
                                            x-show="items.length > 1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.akhlaq-values.index') }}" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 transform hover:-translate-y-0.5">
                        Create Core Value
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
