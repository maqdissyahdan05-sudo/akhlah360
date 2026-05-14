@extends('layouts.app')
@section('title', '360° Rater Mapping')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <form method="GET" class="flex space-x-4">
        <select name="period_id" class="border rounded px-4 py-2" onchange="this.form.submit()">
            @foreach($periods as $p)
                <option value="{{ $p->period_id }}" {{ $selectedPeriodId == $p->period_id ? 'selected' : '' }}>{{ $p->period_name }}</option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('admin.assignments.bulk-create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">+ Bulk Mapping Generation</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Target Employee (Ratee)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evaluator (Rater)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Relationship</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($assignments as $assign)
                <tr>
                    <td class="px-6 py-4 font-bold">{{ $assign->ratee->employee_name }}</td>
                    <td class="px-6 py-4">{{ $assign->rater->employee_name }}</td>
                    <td class="px-6 py-4 capitalize">{{ $assign->relationship_type }}</td>
                    <td class="px-6 py-4">
                        @if($assign->is_completed)
                            <span class="text-green-600 font-bold text-sm">Completed</span>
                        @else
                            <span class="text-yellow-600 font-bold text-sm">Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $assignments->links() }}</div>
    </div>
</div>
@endsection
