<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\InterviewSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'user') {
            $schedules = InterviewSchedule::whereHas('candidate', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['candidate', 'feedbackBy'])
                ->latest()
                ->paginate(10);
        } else {
            $schedules = InterviewSchedule::with(['candidate', 'feedbackBy'])
                ->latest()
                ->paginate(10);
        }

        return view('interviews.index', compact('schedules'));
    }

    public function create()
    {
        if (Auth::user()->role === 'user') {
            abort(403, 'Unauthorized action.');
        }

        $candidates = Candidate::with('vacancy')->get();
        $interviewers = User::where('role', 'hrd')->get();

        return view('interviews.create', compact('candidates', 'interviewers'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'user') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'feedback_by' => 'required|exists:users,id',
            'schedule_date' => 'required|date|after:now',
            'notes' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        InterviewSchedule::create($validated);

        return redirect()->route('interviews.index')
            ->with('success', 'Interview scheduled successfully.');
    }

    public function show(InterviewSchedule $interview)
    {
        return view('interviews.show', compact('interview'));
    }

    public function edit(InterviewSchedule $interview)
    {
        if (Auth::user()->role === 'user') {
            abort(403, 'Unauthorized action.');
        }

        $candidates = Candidate::with('vacancy')->get();
        $interviewers = User::where('role', 'hrd')->get();

        return view('interviews.edit', compact('interview', 'candidates', 'interviewers'));
    }

    public function update(Request $request, InterviewSchedule $interview)
    {
        if (Auth::user()->role === 'user') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'feedback_by' => 'required|exists:users,id',
            'schedule_date' => 'required|date|after:now',
            'notes' => 'nullable|string'
        ]);

        $interview->update($validated);

        return redirect()->route('interviews.index')
            ->with('success', 'Interview updated successfully.');
    }

    public function destroy(InterviewSchedule $interview)
    {
        if (Auth::user()->role === 'user') {
            abort(403, 'Unauthorized action.');
        }

        $interview->delete();

        return redirect()->route('interviews.index')
            ->with('success', 'Interview deleted successfully.');
    }

    public function approve(InterviewSchedule $interview)
    {
        if (Auth::user()->role !== 'user' || $interview->candidate->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $interview->update([
            'status' => 'approved',
            'feedback_at' => now()
        ]);

        return back()->with('success', 'Interview has been approved.');
    }

    public function reject(Request $request, InterviewSchedule $interview)
    {
        if (Auth::user()->role !== 'user' || $interview->candidate->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'feedback' => 'required|string'
        ]);

        $interview->update([
            'status' => 'rejected',
            'feedback' => $request->feedback,
            'feedback_at' => now()
        ]);

        return back()->with('success', 'Interview has been rejected.');
    }

    public function complete(InterviewSchedule $interview)
    {
        if (!in_array(Auth::user()->role, ['hrd', 'super_admin'])) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan interview sudah approved sebelum complete
        if (!$interview->isApproved()) {
            return back()->with('error', 'Interview must be approved before marking as completed.');
        }

        $interview->update([
            'status' => 'completed',
            'feedback_at' => now()
        ]);

        // Redirect langsung ke form input hasil
        return redirect()->route('interview-results.create', $interview->id)
            ->with('success', 'Interview has been marked as completed. Please input the results.');
    }
}
