<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AgentController extends Controller
{
    public function index(): View
    {
        $agents = Agent::with('user')
            ->withCount('properties')
            ->latest()
            ->paginate(15);

        return view('admin.agents.index', compact('agents'));
    }

    public function toggleVerify(Agent $agent): RedirectResponse
    {
        $agent->update(['is_verified' => ! $agent->is_verified]);

        return back()->with('success', 'Agent verification status updated.');
    }
}
