<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Criteria;
use App\Models\Vacancy;
use App\Models\ProfileMatchingResult;
use App\Models\IdealProfileValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileMatchingController extends Controller
{
    public function index(): View
    {
        $vacancies = Vacancy::where('is_active', true)->get();
        return view('profile_matching.index', compact('vacancies'));
    }

    public function process(Request $request): View|RedirectResponse
    {
        $request->validate(['vacancy_id' => 'required|exists:vacancies,id']);
        $vacancy = Vacancy::findOrFail($request->vacancy_id);

        $this->ensureIdealProfileValuesExist($vacancy);

        $candidates = Candidate::where('vacancy_id', $vacancy->id)
            ->with(['criteriaValues.criteria'])
            ->get();

        $criterias = Criteria::all()->each(function ($criteria) {
            $criteria->ahp_weight = $criteria->ahp_weight;
        });
        $idealProfile = IdealProfileValue::where('vacancy_id', $vacancy->id)
            ->pluck('value', 'criteria_id')
            ->toArray();

        $results = $this->calculateProfileMatching($candidates, $criterias, $idealProfile);
        $this->saveResultsToDatabase($results, $vacancy->id);

        return view('profile_matching.results', [
            'vacancy' => $vacancy,
            'results' => $results,
            'criterias' => $criterias,
            'idealProfile' => $idealProfile
        ]);
    }

    private function ensureIdealProfileValuesExist(Vacancy $vacancy): void
    {
        $criterias = Criteria::all();

        foreach ($criterias as $criteria) {
            IdealProfileValue::firstOrCreate(
                [
                    'vacancy_id' => $vacancy->id,
                    'criteria_id' => $criteria->id
                ],
                ['value' => $criteria->type === 'benefit' ? 5 : 1]
            );
        }
    }

    /**
     * @return array<int, array{
     *     candidate: Candidate,
     *     total_gap: float,
     *     total_weighted_gap: float,
     *     final_score: float,
     *     criteria_gaps: array<int, array{value: mixed, gap: float, weight: float}>
     * }>
     */
    private function calculateProfileMatching($candidates, $criterias, array $idealProfile): array
    {
        $results = [];

        foreach ($candidates as $candidate) {
            $totalGap = 0.0;
            $totalWeightedGap = 0.0;
            $totalWeight = 0.0;
            $criteriaGaps = [];

            foreach ($criterias as $criteria) {
                if (!isset($idealProfile[$criteria->id])) {
                    continue;
                }

                $candidateValue = $candidate->criteriaValues
                    ->where('criteria_id', $criteria->id)
                    ->first()->value ?? 0;

                $gap = abs($idealProfile[$criteria->id] - $candidateValue);
                $weight = $criteria->ahp_weight;

                $totalGap += $gap;
                $totalWeightedGap += $gap * $weight;
                $totalWeight += $weight;
                $criteriaGaps[$criteria->id] = [
                    'value' => $candidateValue,
                    'gap' => $gap,
                    'weight' => $weight
                ];
            }

            $averageGap = $totalWeight > 0 ? $totalWeightedGap / $totalWeight : 0;
            $finalScore = 100 - ($averageGap * 20); // Scale to 0-100

            $results[] = [
                'candidate' => $candidate,
                'total_gap' => $totalGap,
                'total_weighted_gap' => $totalWeightedGap,
                'final_score' => $finalScore,
                'criteria_gaps' => $criteriaGaps
            ];
        }

        // Sort by highest score
        usort($results, fn($a, $b) => $b['final_score'] <=> $a['final_score']);

        return $results;
    }

    public function saveIdealValues(Request $request): RedirectResponse
    {
        $request->validate([
            'vacancy_id' => 'required|exists:vacancies,id',
            'ideal_values' => 'required|array',
            'ideal_values.*' => 'required|integer|min:1|max:5',
        ]);

        foreach ($request->ideal_values as $criteriaId => $value) {
            IdealProfileValue::updateOrCreate(
                [
                    'vacancy_id' => $request->vacancy_id,
                    'criteria_id' => $criteriaId
                ],
                ['value' => $value]
            );
        }

        return redirect()->route('profile-matching.results', ['vacancy_id' => $request->vacancy_id])
            ->with('success', 'Ideal values updated successfully!');
    }

    public function results(int $vacancy_id): View
    {
        $vacancy = Vacancy::findOrFail($vacancy_id);
        $candidates = Candidate::where('vacancy_id', $vacancy->id)
            ->with(['criteriaValues.criteria'])
            ->get();

        $criterias = Criteria::all();
        $idealProfile = IdealProfileValue::where('vacancy_id', $vacancy->id)
            ->pluck('value', 'criteria_id')
            ->toArray();

        $results = $this->calculateProfileMatching($candidates, $criterias, $idealProfile);

        return view('profile_matching.results', [
            'vacancy' => $vacancy,
            'results' => $results,
            'criterias' => $criterias,
            'idealProfile' => $idealProfile
        ]);
    }

    public function saveResults(Request $request): RedirectResponse
    {
        $request->validate([
            'vacancy_id' => 'required|exists:vacancies,id'
        ]);
    
        $vacancy = Vacancy::findOrFail($request->vacancy_id);
        $candidates = Candidate::where('vacancy_id', $vacancy->id)
            ->with(['criteriaValues.criteria'])
            ->get();
    
        $criterias = Criteria::all();
        $idealProfile = IdealProfileValue::where('vacancy_id', $vacancy->id)
            ->pluck('value', 'criteria_id')
            ->toArray();
    
        $results = $this->calculateProfileMatching($candidates, $criterias, $idealProfile);
        $this->saveResultsToDatabase($results, $vacancy->id);
    
        // Redirect ke view-results bukan ke results
        return redirect()->route('profile-matching.view-results', ['vacancy_id' => $vacancy->id])
            ->with('success', 'Results saved successfully!');
    }

    private function saveResultsToDatabase(array $results, int $vacancyId): void
    {
        ProfileMatchingResult::whereHas('candidate', function ($q) use ($vacancyId) {
            $q->where('vacancy_id', $vacancyId);
        })->delete();

        $rank = 1;
        foreach ($results as $result) {
            ProfileMatchingResult::create([
                'candidate_id' => $result['candidate']->id,
                'total_gap' => $result['total_gap'] ?? 0,
                'total_weighted_gap' => $result['total_weighted_gap'] ?? 0,
                'final_score' => $result['final_score'],
                'rank' => $rank++,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'notes' => 'Profile matching calculation result'
            ]);
        }
    }

    public function viewResults(int $vacancy_id): View
    {
        $vacancy = Vacancy::findOrFail($vacancy_id);
        $results = ProfileMatchingResult::with(['candidate', 'processedBy'])
            ->whereHas('candidate', fn($q) => $q->where('vacancy_id', $vacancy_id))
            ->orderBy('rank')
            ->get();

        return view('profile_matching.results_list', compact('vacancy', 'results'));
    }
}