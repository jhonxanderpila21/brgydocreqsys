<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use Illuminate\Http\Request;

class ResidentProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $resident = $user->resident;
        $households = Household::orderBy('name')->get();
        return view('resident.profile', compact('resident', 'user', 'households'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'purok_zone' => 'nullable|string|max:100',
            'date_of_birth' => 'required|date',
            'civil_status' => 'required|string|max:50',
            'occupation' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:50',
            'household_id' => 'nullable|exists:households,id'
        ]);

        if ($user->resident) {
            $user->resident->update($validated);
        } else {
            $user->resident()->create($validated);
        }

        return redirect()->route('resident.profile')->with('success', 'Profile updated successfully.');
    }
}
