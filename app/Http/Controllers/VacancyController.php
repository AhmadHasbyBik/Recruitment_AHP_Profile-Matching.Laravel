<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function index()
    {
        $vacancies = Vacancy::all();
        return view('vacancies.index', compact('vacancies'));
    }

    public function create()
    {
        return view('vacancies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'open_date' => 'required|date',
            'close_date' => 'required|date|after:open_date',
            'is_active' => 'sometimes|boolean'
        ]);

        // Hapus baris berikut
        // $validated['is_active'] = $request->has('is_active');

        Vacancy::create($validated);

        return redirect()->route('vacancies.index')
            ->with('success', 'Vacancy created successfully.');
    }

    public function show(Vacancy $vacancy)
    {
        return view('vacancies.show', compact('vacancy'));
    }

    public function edit(Vacancy $vacancy)
    {
        return view('vacancies.edit', compact('vacancy'));
    }

    public function update(Request $request, Vacancy $vacancy)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'open_date' => 'required|date',
            'close_date' => 'required|date|after:open_date',
            'is_active' => 'sometimes|boolean'
        ]);

        // Hapus baris berikut
        // $validated['is_active'] = $request->has('is_active');

        $vacancy->update($validated);

        return redirect()->route('vacancies.index')
            ->with('success', 'Vacancy updated successfully.');
    }

    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();

        return redirect()->route('vacancies.index')
            ->with('success', 'Vacancy deleted successfully.');
    }
}
