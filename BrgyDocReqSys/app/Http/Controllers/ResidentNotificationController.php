<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequestStatusLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ResidentNotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isResident()) {
            abort(403);
        }

        $residentId = $user->resident?->id;
        
        if (!$residentId) {
            return redirect()->route('resident.profile')->with('error', 'Please complete your profile to view notifications.');
        }

        // Fetch notifications and eager load the documentRequest and its documentType
        $notifications = DocumentRequestStatusLog::with(['documentRequest.documentType'])
            ->whereHas('documentRequest', function ($query) use ($residentId) {
                $query->where('resident_id', $residentId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('resident.notifications', compact('notifications'));
    }
}
