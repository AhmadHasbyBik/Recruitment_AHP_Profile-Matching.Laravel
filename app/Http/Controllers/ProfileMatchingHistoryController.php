<?php
// app/Http/Controllers/ProfileMatchingHistoryController.php
namespace App\Http\Controllers;

use App\Models\ProfileMatchingResult;
use App\Models\Vacancy;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileMatchingHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProfileMatchingResult::with(['candidate.vacancy', 'processedBy'])
            ->orderBy('processed_at', 'desc');

        // Filter by vacancy
        if ($request->has('vacancy_id') && $request->vacancy_id != '') {
            $query->whereHas('candidate', function($q) use ($request) {
                $q->where('vacancy_id', $request->vacancy_id);
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('processed_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('processed_at', '<=', $request->date_to);
        }

        // Filter by rank range
        if ($request->has('rank_from') && $request->rank_from != '') {
            $query->where('rank', '>=', $request->rank_from);
        }
        if ($request->has('rank_to') && $request->rank_to != '') {
            $query->where('rank', '<=', $request->rank_to);
        }

        // Filter by score range
        if ($request->has('score_from') && $request->score_from != '') {
            $query->where('final_score', '>=', $request->score_from);
        }
        if ($request->has('score_to') && $request->score_to != '') {
            $query->where('final_score', '<=', $request->score_to);
        }

        // Filter by processed by
        if ($request->has('processed_by') && $request->processed_by != '') {
            if ($request->processed_by == 'system') {
                $query->whereNull('processed_by');
            } else {
                $query->where('processed_by', $request->processed_by);
            }
        }

        $histories = $query->paginate(10);
        $vacancies = Vacancy::where('is_active', true)->get();
        $processors = User::has('profileMatchingResults')->get();
    
        return view('profile_matching.history.index', compact('histories', 'vacancies', 'processors'));
    }

    public function byVacancy($vacancy_id)
    {
        $vacancy = Vacancy::findOrFail($vacancy_id);
        $results = ProfileMatchingResult::whereHas('candidate', function ($q) use ($vacancy_id) {
            $q->where('vacancy_id', $vacancy_id);
        })
            ->with(['candidate', 'processedBy'])
            ->orderBy('rank')
            ->get();

        return view('profile_matching.history.by_vacancy', compact('vacancy', 'results'));
    }

    public function show($id)
{
    $result = ProfileMatchingResult::with([
        'candidate.vacancy',
        'processedBy',
        'candidate.criteriaValues.criteria',
        'vacancy.idealProfileValues' // Tambahkan ini
    ])->findOrFail($id);

    // Ambil ideal values
    $idealValues = $result->vacancy->idealProfileValues->pluck('value', 'criteria_id');

    // Attach ideal values ke masing-masing criteria value
    $result->criteriaValues->each(function ($value) use ($idealValues) {
        $value->ideal_value = $idealValues[$value->criteria_id] ?? 0;
    });

    return view('profile_matching.history.show', compact('result'));
}
}