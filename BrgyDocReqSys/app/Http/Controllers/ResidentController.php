<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $households = Household::orderBy('name')->get();

        $residents = Resident::with('household')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('full_name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('occupation', 'like', "%{$search}%")
                        ->orWhere('contact_number', 'like', "%{$search}%");
                });
            })
            ->when($request->household_id, fn($query, $householdId) => $query->where('household_id', $householdId))
            ->when($request->purok_zone, fn($query, $purokZone) => $query->where('purok_zone', 'like', "%{$purokZone}%"))
            ->orderBy('full_name')
            ->paginate(15);

        return view('residents.index', compact('residents', 'households'));
    }

    public function create()
    {
        $households = Household::orderBy('name')->get();

        return view('residents.create', compact('households'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'household_id' => 'nullable|exists:households,id',
            'full_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'purok_zone' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'civil_status' => 'required|string|max:100',
            'occupation' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:50',
        ]);

        Resident::create($validated);

        return redirect()->route('residents.index')
            ->with('success', 'Resident added successfully.');
    }

    public function edit(Resident $resident)
    {
        $households = Household::orderBy('name')->get();

        return view('residents.edit', compact('resident', 'households'));
    }

    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'household_id' => 'nullable|exists:households,id',
            'full_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'purok_zone' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'civil_status' => 'required|string|max:100',
            'occupation' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:50',
        ]);

        $resident->update($validated);

        return redirect()->route('residents.index')
            ->with('success', 'Resident updated successfully.');
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();

        return redirect()->route('residents.index')
            ->with('success', 'Resident removed successfully.');
    }
}
