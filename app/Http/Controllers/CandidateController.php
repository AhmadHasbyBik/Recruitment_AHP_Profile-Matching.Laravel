<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vacancy;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $query = Candidate::with('vacancy', 'results');

        // Filter by vacancy
        if ($request->has('vacancy_id') && $request->vacancy_id != '') {
            $query->where('vacancy_id', $request->vacancy_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'processed') {
                $query->whereHas('results');
            } elseif ($request->status == 'unprocessed') {
                $query->whereDoesntHave('results');
            } elseif ($request->status == 'top_rank') {
                $query->whereHas('results', function ($q) {
                    $q->where('rank', 1);
                });
            }
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Gunakan withQueryString() untuk mempertahankan parameter filter
        $candidates = $query->paginate(10)->withQueryString();

        $vacancies = Vacancy::where('is_active', true)->get();

        return view('candidates.index', compact('candidates', 'vacancies'));
    }

    public function create()
    {
        // Cek apakah user sudah mendaftarkan kandidat
        if (Auth::user()->candidate) {
            return redirect()->route('dashboard')
                ->with('warning', 'Anda sudah mendaftarkan kandidat.');
        }

        $vacancies = Vacancy::where('is_active', true)->get();
        return view('candidates.create', compact('vacancies'));
    }

    public function store(Request $request)
    {
        // Validasi bahwa user belum memiliki kandidat
        if (Auth::user()->candidate) {
            return redirect()->route('dashboard')
                ->with('warning', 'Anda sudah mendaftarkan kandidat.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'vacancy_id' => 'required|exists:vacancies,id',
            'resume' => 'nullable|string',
        ]);

        $candidate = new Candidate($request->only([
            'name',
            'email',
            'phone',
            'address',
            'vacancy_id',
            'resume'
        ]));
        
        $candidate->user_id = Auth::id();
        $candidate->save();

        return redirect()->route('dashboard')
            ->with('success', 'Kandidat berhasil didaftarkan.');
    }

    public function show(Candidate $candidate)
    {
        $candidate->load('vacancy', 'criteriaValues.criteria');
        return view('candidates.show', compact('candidate'));
    }

    public function edit(Candidate $candidate)
    {
        $vacancies = Vacancy::where('is_active', true)->get();
        $criterias = Criteria::all();
        $candidate->load('criteriaValues');
        return view('candidates.edit', compact('candidate', 'vacancies', 'criterias'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $candidate->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'vacancy_id' => 'required|exists:vacancies,id',
            'resume' => 'nullable|string',
        ]);

        $candidate->update($request->only([
            'name',
            'email',
            'phone',
            'address',
            'vacancy_id',
            'resume'
        ]));

        // Update criteria values
        $criterias = Criteria::all();
        foreach ($criterias as $criteria) {
            $value = $request->input('criteria_' . $criteria->id);
            $criteriaValue = $candidate->criteriaValues()->where('criteria_id', $criteria->id)->first();

            if ($value !== null) {
                if ($criteriaValue) {
                    $criteriaValue->update(['value' => $value]);
                } else {
                    $candidate->criteriaValues()->create([
                        'criteria_id' => $criteria->id,
                        'value' => $value
                    ]);
                }
            } elseif ($criteriaValue) {
                $criteriaValue->delete();
            }
        }

        return redirect()->route('candidates.index')
            ->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate)
    {
        $candidate->delete();

        return redirect()->route('candidates.index')
            ->with('success', 'Candidate deleted successfully.');
    }

    public function interviews(Candidate $candidate)
    {
        $interviews = $candidate->interviewSchedules()
            ->with('feedbackBy')
            ->latest()
            ->paginate(10);

        return view('candidates.interviews', compact('candidate', 'interviews'));
    }
}
