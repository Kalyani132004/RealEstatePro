<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnquiryRequest;
use App\Mail\EnquiryNotifyAgentMail;
use App\Mail\EnquiryReceivedMail;
use App\Models\Enquiry;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    /**
     * Store a new enquiry, submitted via AJAX from the property details.
     */
    public function store(EnquiryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $property = Property::with('agent.user')->findOrFail($validated['property_id']);

        $enquiry = Enquiry::create([
            'property_id' => $property->id,
            'user_id' => auth()->id(),
            'agent_id' => $property->agent_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
            'status' => Enquiry::STATUS_NEW,
        ]);

        $enquiry->setRelation('property', $property);

        if ($property->agent?->user?->email) {
            Mail::to($property->agent->user->email)->send(new EnquiryNotifyAgentMail($enquiry));
        }

        Mail::to($enquiry->email)->send(new EnquiryReceivedMail($enquiry));

        return response()->json([
            'message' => 'Your enquiry has been sent to the agent!',
            'enquiry' => $enquiry,
        ], 201);
    }
}
