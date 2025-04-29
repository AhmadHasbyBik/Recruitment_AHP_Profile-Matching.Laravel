<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\CriteriaStatus;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $criterias = Criteria::with('status')->get();
        return view('criterias.index', compact('criterias'));
    }

    public function create()
    {
        $statuses = CriteriaStatus::getDropdownOptions();
        $types = Criteria::getTypeOptions();
        return view('criterias.create', compact('statuses', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:criterias',
            'name' => 'required|string|max:255',
            'criteria_status_id' => 'required|exists:criteria_statuses,id',
            'type' => 'required|in:'.implode(',', array_keys(Criteria::getTypeOptions())),
            'description' => 'nullable|string',
        ]);

        Criteria::create($request->all());

        return redirect()->route('criterias.index')
            ->with('success', 'Criteria created successfully.');
    }

    public function show(Criteria $criteria)
    {
        return view('criterias.show', compact('criteria'));
    }

    public function edit(Criteria $criteria)
    {
        $statuses = CriteriaStatus::getDropdownOptions();
        $types = Criteria::getTypeOptions();
        return view('criterias.edit', compact('criteria', 'statuses', 'types'));
    }

    public function update(Request $request, Criteria $criteria)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:criterias,code,'.$criteria->id,
            'name' => 'required|string|max:255',
            'criteria_status_id' => 'required|exists:criteria_statuses,id',
            'type' => 'required|in:'.implode(',', array_keys(Criteria::getTypeOptions())),
            'description' => 'nullable|string',
        ]);

        $criteria->update($request->all());

        return redirect()->route('criterias.index')
            ->with('success', 'Criteria updated successfully.');
    }

    public function destroy(Criteria $criteria)
    {
        try {
            $criteria->delete();
            return redirect()->route('criterias.index')
                ->with('success', 'Criteria deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('criterias.index')
                ->with('error', 'Error deleting criteria: ' . $e->getMessage());
        }
    }
}