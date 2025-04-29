<?php

namespace App\Http\Controllers;

use App\Models\CriteriaStatus;
use Illuminate\Http\Request;

class CriteriaStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $statuses = CriteriaStatus::all();
        return view('criteria_statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('criteria_statuses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:criteria_statuses',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        CriteriaStatus::create($request->all());

        return redirect()->route('criteria_statuses.index')
            ->with('success', 'Criteria status created successfully.');
    }

    public function show(CriteriaStatus $criteriaStatus)
    {
        return view('criteria_statuses.show', compact('criteriaStatus'));
    }

    public function edit(CriteriaStatus $criteriaStatus)
    {
        return view('criteria_statuses.edit', compact('criteriaStatus'));
    }

    public function update(Request $request, CriteriaStatus $criteriaStatus)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:criteria_statuses,code,'.$criteriaStatus->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $criteriaStatus->update($request->all());

        return redirect()->route('criteria_statuses.index')
            ->with('success', 'Criteria status updated successfully.');
    }

    public function destroy(CriteriaStatus $criteriaStatus)
    {
        if ($criteriaStatus->criterias()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete status because it has associated criterias.');
        }

        $criteriaStatus->delete();

        return redirect()->route('criteria_statuses.index')
            ->with('success', 'Criteria status deleted successfully.');
    }
}