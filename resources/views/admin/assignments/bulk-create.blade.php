@extends('layouts.app')
@section('title', 'Create Bulk 360° Mapping')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form action="{{ route('admin.assignments.bulk-store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Select Period</label>
            <select name="period_id" class="w-full border rounded-lg px-4 py-2" required>
                @foreach($periods as $p)
                    <option value="{{ $p->period_id }}">{{ $p->period_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Target Employee (Ratee)</label>
            <select name="ratee_id" class="w-full border rounded-lg px-4 py-2" required>
                @foreach($employees as $e)
                    <option value="{{ $e->employee_id }}">{{ $e->employee_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="pt-4">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Save Mapping</button>
            <a href="{{ route('admin.assignments.index') }}" class="ml-2 text-gray-500">Cancel</a>
        </div>
    </form>
</div>
@endsection
