<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use App\Models\AkhlaqValue;
use App\Models\Assessment;
use App\Models\Assignment;
use App\Models\AssessmentResult;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function show(Assignment $assignment): View|RedirectResponse
    {
        // Security check
        if ($assignment->rater_id !== auth()->user()->employee_id) {
            abort(403, 'Akses ditolak.');
        }

        if ($assignment->is_completed) {
            return redirect()->route('assessment.dashboard')->with('error', 'This assessment has already been completed.');
        }

        $assignment->load(['ratee.department', 'period']);
        
        $akhlaqValues = AkhlaqValue::with('indicators')->get();

        return view('assessment.form', compact('assignment', 'akhlaqValues'));
    }

    public function store(Request $request, Assignment $assignment): RedirectResponse
    {
        if ($assignment->rater_id !== auth()->user()->employee_id) {
            abort(403);
        }

        if ($assignment->is_completed) {
            return back()->with('error', 'Penilaian sudah diselesaikan.');
        }

        $validated = $request->validate([
            'scores'   => ['required', 'array'],
            'scores.*' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['scores'] as $indicatorId => $score) {
                Assessment::updateOrCreate(
                    ['assignment_id' => $assignment->assignment_id, 'indicator_id' => $indicatorId],
                    ['score' => $score]
                );
            }

            $assignment->update([
                'is_completed' => true,
                'completed_at' => now(),
            ]);

            // Recalculate Assessment Results for the ratee
            $this->recalculateResults($assignment->period_id, $assignment->ratee_id);

            AuditLog::record('SUBMIT assessment', 'assignments', $assignment->assignment_id);

            DB::commit();
            return redirect()->route('assessment.tasks')->with('success', 'Assessment submitted successfully. Thank you for your feedback!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while saving the assessment: ' . $e->getMessage());
        }
    }

    /**
     * Logic to calculate aggregated scores
     */
    private function recalculateResults(int $periodId, int $rateeId)
    {
        // Get all completed assessments for this ratee in this period
        $completedAssignments = Assignment::where('period_id', $periodId)
            ->where('ratee_id', $rateeId)
            ->where('is_completed', true)
            ->get();

        $scores = [
            'self'        => [],
            'peer'        => [],
            'superior'    => [],
            'subordinate' => [],
        ];

        foreach ($completedAssignments as $assignment) {
            $avgScore = Assessment::where('assignment_id', $assignment->assignment_id)->avg('score');
            if ($avgScore !== null) {
                $scores[$assignment->relationship_type][] = $avgScore;
            }
        }

        // Calculate averages per category
        $avgSelf = count($scores['self']) ? collect($scores['self'])->avg() : null;
        $avgPeer = count($scores['peer']) ? collect($scores['peer'])->avg() : null;
        $avgSup  = count($scores['superior']) ? collect($scores['superior'])->avg() : null;
        $avgSub  = count($scores['subordinate']) ? collect($scores['subordinate'])->avg() : null;

        // Weighting formula (example weight: self 10%, peer 30%, sup 40%, sub 20%)
        // If some categories are empty, we adjust weights dynamically, but for simplicity we take average of available others
        $otherScores = array_filter([$avgPeer, $avgSup, $avgSub], fn($v) => $v !== null);
        $avgOthers = count($otherScores) ? collect($otherScores)->avg() : null;

        $finalScore = null;
        if ($avgSelf !== null && $avgOthers !== null) {
             // Example 30% self, 70% others
            $finalScore = ($avgSelf * 0.3) + ($avgOthers * 0.7);
        } elseif ($avgOthers !== null) {
            $finalScore = $avgOthers;
        } elseif ($avgSelf !== null) {
            $finalScore = $avgSelf;
        }

        $gapScore = null;
        if ($avgSelf !== null && $avgOthers !== null) {
            $gapScore = $avgSelf - $avgOthers; // Positive means overestimating self, negative means underestimating
        }

        AssessmentResult::updateOrCreate(
            ['period_id' => $periodId, 'employee_id' => $rateeId],
            [
                'self_score'        => $avgSelf,
                'peer_score'        => $avgPeer,
                'superior_score'    => $avgSup,
                'subordinate_score' => $avgSub,
                'final_score'       => $finalScore,
                'gap_score'         => $gapScore,
            ]
        );
    }
}
