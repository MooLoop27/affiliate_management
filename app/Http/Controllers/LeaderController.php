<?php

namespace App\Http\Controllers;

use App\Models\Leader;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeaderController extends Controller
{
    public function index(): View
    {
        $leaders = Leader::withCount('transactions', 'recipients')
            ->latest()
            ->paginate(10);

        return view('master-data.leaders.index', compact('leaders'));
    }

    public function create(): View
    {
        return view('master-data.leaders.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'leader_name' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $validated['leader_code'] = Leader::generateCode();

        Leader::create($validated);

        ActivityLog::log('Created Leader: ' . $validated['leader_name']);

        return redirect()->route('leaders.index')
            ->with('success', 'Leader created successfully.');
    }

    public function show(Leader $leader): View
    {
        $leader->load(['transactions.singaporePartner', 'recipients']);
        return view('master-data.leaders.show', compact('leader'));
    }

    public function edit(Leader $leader): View
    {
        return view('master-data.leaders.edit', compact('leader'));
    }

    public function update(Request $request, Leader $leader): RedirectResponse
    {
        $validated = $request->validate([
            'leader_name' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $leader->update($validated);

        ActivityLog::log('Updated Leader: ' . $leader->leader_name);

        return redirect()->route('leaders.index')
            ->with('success', 'Leader updated successfully.');
    }

    public function destroy(Leader $leader): RedirectResponse
    {
        $name = $leader->leader_name;
        $leader->delete();

        ActivityLog::log('Deleted Leader: ' . $name);

        return redirect()->route('leaders.index')
            ->with('success', 'Leader deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $leaders = Leader::where('leader_name', 'like', "%{$query}%")
            ->orWhere('leader_code', 'like', "%{$query}%")
            ->active()
            ->get(['id', 'leader_code', 'leader_name']);

        return response()->json($leaders);
    }
}

