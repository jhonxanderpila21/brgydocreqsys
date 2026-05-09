<?php

namespace App\Http\Controllers;

use App\Models\Household;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    public function index()
    {
        $households = Household::withCount('residents')
            ->orderBy('name')
            ->paginate(15);

        return view('households.index', compact('households'));
    }

    public function create()
    {
        return view('households.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'purok_zone' => 'nullable|string|max:255',
        ]);

        Household::create($validated);

        return redirect()->route('households.index')
            ->with('success', 'Household created successfully.');
    }

    public function edit(Household $household)
    {
        return view('households.edit', compact('household'));
    }

    public function update(Request $request, Household $household)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'purok_zone' => 'nullable|string|max:255',
        ]);

        $household->update($validated);

        return redirect()->route('households.index')
            ->with('success', 'Household updated successfully.');
    }

    public function destroy(Household $household)
    {
        $household->delete();

        return redirect()->route('households.index')
            ->with('success', 'Household removed successfully.');
    }
}
