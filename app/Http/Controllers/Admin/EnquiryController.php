<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    public function index(): View
    {
        $enquiries = Enquiry::with(['property', 'agent.user'])
            ->latest()
            ->paginate(15);

        return view('admin.enquiries.index', compact('enquiries'));
    }
}
