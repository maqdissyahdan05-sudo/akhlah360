<?php

namespace App\Http\Controllers\AdminHr;

use App\Http\Controllers\Controller;
use App\Models\AkhlaqValue;
use App\Models\AssessmentIndicator;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AkhlaqValueController extends Controller
{
    public function index(): View
    {
        $values = AkhlaqValue::withCount('indicators')->orderBy('value_id')->get();
        return view('admin.akhlaq-values.index', compact('values'));
    }

    public function create(): View
    {
        return view('admin.akhlaq-values.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'value_name'  => ['required', 'string', 'max:100', 'unique:akhlaq_values,value_name'],
            'description' => ['nullable', 'string'],
            // Inline indicators support
            'indicators'              => ['nullable', 'array'],
            'indicators.*.statement' => ['required_with:indicators', 'string'],
        ]);

        $value = AkhlaqValue::create([
            'value_name'  => $validated['value_name'],
            'description' => $validated['description'] ?? null,
        ]);

        foreach (($validated['indicators'] ?? []) as $indicator) {
            AssessmentIndicator::create([
                'value_id'            => $value->value_id,
                'indicator_statement' => $indicator['statement'],
            ]);
        }

        AuditLog::record('CREATE akhlaq value', 'akhlaq_values', $value->value_id, [], $value->toArray());

        return redirect()->route('admin.akhlaq-values.index')->with('success', 'AKHLAK Core Value added successfully.');
    }

    public function show(AkhlaqValue $akhlaqValue): View
    {
        $akhlaqValue->load('indicators');
        return view('admin.akhlaq-values.show', compact('akhlaqValue'));
    }

    public function edit(AkhlaqValue $akhlaqValue): View
    {
        $akhlaqValue->load('indicators');
        return view('admin.akhlaq-values.edit', compact('akhlaqValue'));
    }

    public function update(Request $request, AkhlaqValue $akhlaqValue): RedirectResponse
    {
        $validated = $request->validate([
            'value_name'  => ['required', 'string', 'max:100', 'unique:akhlaq_values,value_name,' . $akhlaqValue->value_id . ',value_id'],
            'description' => ['nullable', 'string'],
            'indicators'              => ['nullable', 'array'],
            'indicators.*.id'         => ['nullable', 'exists:assessment_indicators,indicator_id'],
            'indicators.*.statement'  => ['required_with:indicators', 'string'],
        ]);

        $old = $akhlaqValue->toArray();
        $akhlaqValue->update([
            'value_name'  => $validated['value_name'],
            'description' => $validated['description'] ?? null,
        ]);

        // Sync indicators: delete removed, update existing, add new
        $incomingIds = collect($validated['indicators'] ?? [])->pluck('id')->filter()->all();
        $akhlaqValue->indicators()->whereNotIn('indicator_id', $incomingIds)->delete();

        foreach (($validated['indicators'] ?? []) as $ind) {
            AssessmentIndicator::updateOrCreate(
                ['indicator_id' => $ind['id'] ?? null],
                ['value_id' => $akhlaqValue->value_id, 'indicator_statement' => $ind['statement']]
            );
        }

        AuditLog::record('UPDATE akhlaq value', 'akhlaq_values', $akhlaqValue->value_id, $old, $akhlaqValue->fresh()->toArray());

        return redirect()->route('admin.akhlaq-values.index')->with('success', 'AKHLAK Core Value updated successfully.');
    }

    public function destroy(AkhlaqValue $akhlaqValue): RedirectResponse
    {
        AuditLog::record('DELETE akhlaq value', 'akhlaq_values', $akhlaqValue->value_id, $akhlaqValue->toArray(), []);
        $akhlaqValue->delete(); // Cascades to indicators

        return redirect()->route('admin.akhlaq-values.index')->with('success', 'Core Value deleted successfully.');
    }
}
