<?php

namespace App\Http\Controllers;

use App\Models\InterviewSchedule;
use App\Models\InterviewResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewResultController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'user') {
            $results = InterviewResult::whereHas('interviewSchedule.candidate', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['interviewSchedule.candidate.vacancy', 'user'])
                ->latest()
                ->get();
        } else {
            $results = InterviewResult::with(['interviewSchedule.candidate.vacancy', 'user'])
                ->latest()
                ->get();
        }

        return view('interview-results.index', compact('results'));
    }

    public function create(InterviewSchedule $interview)
    {
        // Hanya HRD atau admin yang bisa input hasil interview
        if (!in_array(Auth::user()->role, ['hrd', 'super_admin'])) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan interview sudah selesai
        if (!$interview->isCompleted()) {
            return redirect()->back()
                ->with('error', 'Interview must be completed before submitting results.');
        }

        // Cek apakah sudah ada hasil
        if ($interview->hasResult()) {
            return redirect()->route('interview-results.show', $interview->result->id);
        }

        return view('interview-results.create', compact('interview'));
    }

    public function store(Request $request, InterviewSchedule $interview)
    {
        if (!in_array(Auth::user()->role, ['hrd', 'super_admin'])) {
            abort(403, 'Unauthorized action.');
        }

        if (!$interview->isCompleted()) {
            return redirect()->back()
                ->with('error', 'Interview must be completed before submitting results.');
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'strengths' => 'required|string',
            'weaknesses' => 'required|string',
            'recommendation' => 'required|string',
            'notes' => 'nullable|string',
            'decision' => 'required|in:accepted,rejected,hold'
        ]);

        $validated['user_id'] = Auth::id();
        $validated['interview_schedule_id'] = $interview->id;

        $result = InterviewResult::create($validated);

        return redirect()->route('interview-results.show', $result->id)
            ->with('success', 'Interview result has been saved successfully.');
    }

    public function show(InterviewResult $interviewResult)
    {
        $interview = $interviewResult->interviewSchedule;

        // Hanya yang terkait yang bisa melihat (HRD, admin, atau user pemilik candidate)
        if (
            !in_array(Auth::user()->role, ['hrd', 'super_admin']) &&
            Auth::id() !== $interview->candidate->user_id
        ) {
            abort(403, 'Unauthorized action.');
        }

        return view('interview-results.show', compact('interviewResult', 'interview'));
    }

    public function edit(InterviewResult $interviewResult)
    {
        if (!in_array(Auth::user()->role, ['hrd', 'super_admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $interview = $interviewResult->interviewSchedule;
        return view('interview-results.edit', compact('interviewResult', 'interview'));
    }

    public function update(Request $request, InterviewResult $interviewResult)
    {
        if (!in_array(Auth::user()->role, ['hrd', 'super_admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'strengths' => 'required|string',
            'weaknesses' => 'required|string',
            'recommendation' => 'required|string',
            'notes' => 'nullable|string',
            'decision' => 'required|in:accepted,rejected,hold'
        ]);

        $interviewResult->update($validated);

        return redirect()->route('interview-results.show', $interviewResult->id)
            ->with('success', 'Interview result has been updated successfully.');
    }

    public function destroy(InterviewResult $interviewResult)
    {
        if (!in_array(Auth::user()->role, ['hrd', 'super_admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $interviewResult->delete();

        return redirect()->route('interview-results.index')
            ->with('success', 'Interview result has been deleted successfully.');
    }
}
