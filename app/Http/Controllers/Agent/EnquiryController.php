<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $enquiries = $request->user()->agent
            ->enquiries()
            ->with('property')
            ->latest()
            ->paginate(10);

        return view('agent.enquiries', compact('enquiries'));
    }

    /**
     * AJAX status update from the <select> in agent/enquiries.blade.php (Phase 6).
     */
    public function updateStatus(Request $request, Enquiry $enquiry): JsonResponse
    {
        $this->authorize('updateStatus', $enquiry);

        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,closed'],
        ]);

        $enquiry->update(['status' => $validated['status']]);

        return response()->json(['message' => 'Status updated.', 'status' => $enquiry->status]);
    }
}
